<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Services\InvoicePaymentLedger;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class InvoicePayments extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    #[Locked]
    public ?int $customerId = null;
    public bool $drawerOpen = false;
    public string $paymentSearch = '';
    public string $invoiceFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'statusFilter'])) {
            $this->resetPage();
        }
        if (in_array($property, ['paymentSearch', 'invoiceFilter', 'dateFrom', 'dateTo'])) {
            $this->resetPage('paymentsPage');
            $this->resetValidation();
            $this->validateDates();
        }
    }

    public function clearCustomerFilters(): void
    {
        $this->reset('search', 'statusFilter');
        $this->resetPage();
    }

    public function getPayment(int $id): void
    {
        Customer::findOrFail($id);
        $this->customerId = $id;
        $this->clearPaymentFilters();
        $this->resetPage('invoicesPage');
        $this->drawerOpen = true;
        $this->dispatch('customer-payments-opened');
    }

    public function clearPaymentFilters(): void
    {
        $this->reset('paymentSearch', 'invoiceFilter', 'dateFrom', 'dateTo');
        $this->resetValidation();
        $this->resetPage('paymentsPage');
    }

    public function openInvoice(int $id, bool $recordPayment = false): void
    {
        abort_unless($this->customerId && DB::table('customer_order')->where('customer_id', $this->customerId)->where('order_id', $id)->exists(), 404);
        $this->dispatch('open-payment-invoice', id: $id, tab: $recordPayment ? 'payments' : 'customer-info')->to(InvoiceItem::class);
    }

    #[On('display-message')]
    public function refreshPayments(): void
    {
        // Re-render balances after the existing invoice editor saves or deletes a payment.
    }

    protected function customerQuery()
    {
        return app(InvoicePaymentLedger::class)->customers()
            ->when(trim($this->search) !== '', function ($query) {
                $term = '%'.trim($this->search).'%';
                $query->where(function ($query) use ($term) {
                    $query->where('customers.company', 'like', $term)
                        ->orWhereIn('customers.id', DB::table('customer_order')
                            ->leftJoin('order_payment', 'order_payment.order_id', '=', 'customer_order.order_id')
                            ->select('customer_order.customer_id')
                            ->where(function ($query) use ($term) {
                                $query->where('customer_order.order_id', trim(ltrim($this->search, '#')))
                                    ->orWhere('order_payment.ref', 'like', $term);
                            }));
                });
            })
            ->when($this->statusFilter === 'outstanding', fn ($query) => $query->where('outstanding', '>', 0))
            ->when($this->statusFilter === 'paid', fn ($query) => $query->where('outstanding', '=', 0));
    }

    protected function paymentQuery()
    {
        return app(InvoicePaymentLedger::class)->paymentsFor($this->customerId ?? 0)
            ->when(trim($this->paymentSearch) !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('ref', 'like', '%'.trim($this->paymentSearch).'%')
                        ->orWhere('order_id', trim(ltrim($this->paymentSearch, '#')));
                });
            })
            ->when($this->invoiceFilter !== '', fn ($query) => $query->where('order_id', $this->invoiceFilter))
            ->when($this->validDate($this->dateFrom), fn ($query) => $query->where('created_at', '>=', $this->dateFrom.' 00:00:00'))
            ->when($this->validDate($this->dateTo), fn ($query) => $query->where('created_at', '<', \Carbon\Carbon::parse($this->dateTo)->addDay()->format('Y-m-d')));
    }

    protected function validDate(string $value): bool
    {
        return $value !== '' && validator(['date' => $value], ['date' => 'date_format:Y-m-d'])->passes();
    }

    protected function validateDates(): void
    {
        $this->validate([
            'dateFrom' => 'nullable|date_format:Y-m-d',
            'dateTo' => 'nullable|date_format:Y-m-d'.($this->dateFrom !== '' ? '|after_or_equal:dateFrom' : ''),
        ], ['dateTo.after_or_equal' => 'The end date must be on or after the start date.']);
    }

    public function exportPayments()
    {
        abort_unless($this->customerId, 404);
        $this->validateDates();
        $query = $this->paymentQuery()->orderByDesc('created_at')->orderByDesc('id');

        return response()->streamDownload(function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Payment date', 'Invoice', 'Reference', 'Amount received'], ',', '"', '');
            foreach ($query->cursor() as $payment) {
                $reference = (string) $payment->ref;
                if (preg_match('/^[\s]*[=+@\-\t\r\n]/', $reference)) {
                    $reference = "'".$reference;
                }
                fputcsv($file, [$payment->created_at, $payment->order_id, $reference, number_format((float) $payment->amount, 2, '.', '')], ',', '"', '');
            }
            fclose($file);
        }, 'customer-'.$this->customerId.'-payments.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render()
    {
        $query = $this->customerQuery();
        $summary = DB::query()->fromSub(clone $query, 'filtered')
            ->selectRaw('COUNT(*) as companies, COALESCE(SUM(invoiced), 0) as invoiced, COALESCE(SUM(received), 0) as received, COALESCE(SUM(outstanding), 0) as outstanding')->first();
        // Payment activity determines the customer order, independently of invoice status/date.
        $customers = $query->orderByRaw('last_payment IS NULL')->orderByDesc('last_payment')
            ->orderBy('customers.id')->paginate(12);

        $customer = null;
        $customerTotals = null;
        $payments = null;
        $invoices = null;
        $invoiceOptions = collect();
        $outstandingInvoices = collect();
        $filteredReceived = 0;
        if ($this->customerId && $this->drawerOpen) {
            $ledger = app(InvoicePaymentLedger::class);
            $customer = Customer::find($this->customerId);
            $customerTotals = $ledger->customers()->where('customers.id', $this->customerId)->first();
            $invoiceQuery = DB::query()->fromSub($ledger->invoices(), 'ledger')->where('customer_id', $this->customerId);
            $invoices = (clone $invoiceQuery)->orderByDesc('id')->paginate(12, ['*'], 'invoicesPage');
            $invoiceOptions = (clone $invoiceQuery)->orderByDesc('id')->get(['id', 'method']);
            $outstandingInvoices = (clone $invoiceQuery)->where('outstanding', '>', 0)->orderByDesc('outstanding')->get();
            $filteredReceived = (clone $this->paymentQuery())->sum('amount');
            $payments = $this->paymentQuery()->orderByDesc('created_at')->orderByDesc('id')->paginate(15, ['*'], 'paymentsPage');
        }

        return view('livewire.invoice-payments', compact('customers', 'summary', 'customer', 'customerTotals', 'payments', 'invoices', 'invoiceOptions', 'outstandingInvoices', 'filteredReceived'))
            ->layoutData(['pageName' => 'Invoice payments'])->title('Invoice payments');
    }
}

