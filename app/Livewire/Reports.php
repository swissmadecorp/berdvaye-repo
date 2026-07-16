<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    #[Url]
    public string $activeTab = 'memo';

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $dateFrom = '';

    #[Url(except: '')]
    public string $dateTo = '';

    #[Url(except: '')]
    public string $customerId = '';

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    private const INVOICE_EXCLUDED_METHODS = ['On Memo', 'On Hold', 'Repair'];

    private const SORTABLE_COLUMNS = [
        'id' => 'orders.id',
        'created_at' => 'orders.created_at',
        'customer' => 'orders.b_company',
        'total' => 'orders.total',
        'balance' => 'balance',
    ];

    public function mount(): void
    {
        if (! in_array($this->activeTab, ['memo', 'invoice'], true)) {
            $this->activeTab = 'memo';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedCustomerId(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['memo', 'invoice'], true)) {
            $this->activeTab = $tab;
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'dateFrom', 'dateTo', 'customerId');
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if (! array_key_exists($column, self::SORTABLE_COLUMNS)) {
            return;
        }

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function customerName(Order $order): string
    {
        if (filled($order->b_company)) {
            return $order->b_company;
        }

        $billingName = trim($order->b_firstname.' '.$order->b_lastname);

        if ($billingName !== '') {
            return $billingName;
        }

        if (filled($order->s_company)) {
            return $order->s_company;
        }

        return trim($order->s_firstname.' '.$order->s_lastname) ?: 'Unknown customer';
    }

    public function dueDate(Order $order): ?Carbon
    {
        $terms = trim((string) $order->payment_options);
        $days = null;

        if (preg_match('/Net[-\s]?(\d+)/i', $terms, $matches)) {
            $days = (int) $matches[1];
        } elseif (strcasecmp($terms, 'Due upon receipt') === 0) {
            $days = 0;
        }

        return $days === null ? null : $order->created_at->copy()->addDays($days);
    }

    public function isOverdue(Order $order): bool
    {
        $dueDate = $this->dueDate($order);

        return $dueDate !== null && $dueDate->isBefore(today());
    }

    private function balanceExpression(): string
    {
        return '(orders.total - COALESCE(payment_totals.paid_amount, 0) - COALESCE(return_totals.returned_amount, 0))';
    }

    private function outstandingQuery(string $type): Builder
    {
        $paymentTotals = DB::table('order_payment')
            ->select('order_id', DB::raw('SUM(amount) AS paid_amount'))
            ->groupBy('order_id');

        $returnTotals = DB::table('order_returns')
            ->select('order_id', DB::raw('SUM(amount * qty) AS returned_amount'))
            ->groupBy('order_id');

        $query = Order::query()
            ->select('orders.*')
            ->selectRaw($this->balanceExpression().' AS balance')
            ->leftJoinSub($paymentTotals, 'payment_totals', 'payment_totals.order_id', '=', 'orders.id')
            ->leftJoinSub($returnTotals, 'return_totals', 'return_totals.order_id', '=', 'orders.id')
            ->where('orders.status', 0)
            ->whereRaw($this->balanceExpression().' > 0.005');

        if ($type === 'memo') {
            $query->where('orders.method', 'On Memo');
        } else {
            $query->whereNotIn('orders.method', self::INVOICE_EXCLUDED_METHODS);
        }

        return $this->applyFilters($query);
    }

    private function applyFilters(Builder $query): Builder
    {
        $search = trim($this->search);

        $query->when($search !== '', function (Builder $query) use ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('orders.id', 'like', '%'.$search.'%')
                    ->orWhere('orders.po', 'like', '%'.$search.'%')
                    ->orWhere('orders.b_company', 'like', '%'.$search.'%')
                    ->orWhere('orders.b_firstname', 'like', '%'.$search.'%')
                    ->orWhere('orders.b_lastname', 'like', '%'.$search.'%')
                    ->orWhere('orders.s_company', 'like', '%'.$search.'%');
            });
        });

        $query->when($this->dateFrom !== '', fn (Builder $query) => $query->whereDate('orders.created_at', '>=', $this->dateFrom));
        $query->when($this->dateTo !== '', fn (Builder $query) => $query->whereDate('orders.created_at', '<=', $this->dateTo));
        $query->when($this->customerId !== '', function (Builder $query) {
            $query->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('customer_order')
                    ->whereColumn('customer_order.order_id', 'orders.id')
                    ->where('customer_order.customer_id', $this->customerId);
            });
        });

        return $query;
    }

    private function reportCustomers()
    {
        return Customer::query()
            ->select('customers.id', 'customers.company', 'customers.firstname', 'customers.lastname')
            ->join('customer_order', 'customer_order.customer_id', '=', 'customers.id')
            ->join('orders', 'orders.id', '=', 'customer_order.order_id')
            ->where('orders.status', 0)
            ->where(function ($query) {
                $query->where('orders.method', 'On Memo')
                    ->orWhereNotIn('orders.method', self::INVOICE_EXCLUDED_METHODS);
            })
            ->distinct()
            ->orderByRaw("CASE WHEN customers.company = '' OR customers.company IS NULL THEN customers.lastname ELSE customers.company END")
            ->get();
    }

    public function render()
    {
        $memoQuery = $this->outstandingQuery('memo');
        $invoiceQuery = $this->outstandingQuery('invoice');

        $memoBalance = (float) (clone $memoQuery)->sum(DB::raw($this->balanceExpression()));
        $invoiceBalance = (float) (clone $invoiceQuery)->sum(DB::raw($this->balanceExpression()));
        $memoCount = (clone $memoQuery)->count('orders.id');
        $invoiceCount = (clone $invoiceQuery)->count('orders.id');

        $orders = $this->activeTab === 'memo' ? $memoQuery : $invoiceQuery;
        $sortColumn = self::SORTABLE_COLUMNS[$this->sortBy] ?? 'orders.id';

        if ($sortColumn === 'balance') {
            $orders->orderByRaw($this->balanceExpression().' '.$this->sortDirection);
        } else {
            $orders->orderBy($sortColumn, $this->sortDirection);
        }

        $orders = $orders
            ->with('customers:id,company,firstname,lastname')
            ->withCount('products')
            ->paginate(10);

        return view('livewire.reports', [
            'orders' => $orders,
            'customers' => $this->reportCustomers(),
            'memoBalance' => $memoBalance,
            'invoiceBalance' => $invoiceBalance,
            'memoCount' => $memoCount,
            'invoiceCount' => $invoiceCount,
        ])->layoutData(['pageName' => 'Reports'])->title('Reports');
    }
}
