<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Mail\GMailer;
use Livewire\Attributes\On;
use Livewire\Attributes\Js;
use Livewire\Attributes\Url;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use App\Models\Payment;
use Jantinnerezo\LivewireAlert\Enums\Position;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Libs\SearchCriteriaTrait;

class Invoices extends Component
{
    use WithPagination, SearchCriteriaTrait;

    public $page = 1;
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'status' => ['except' => true]
    ];

    #[Url(keep: true)]
    public $search = "";

    public $currentInvoiceId;
    public $textPerson;
    public $order = null;
    public $status = 0;
    public $sql = '';

    public function doSort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection == "ASC" ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $column;
        $this->sortDirection = "DESC";
    }

    public function setCurrentInvoiceId($id) {
        $this->currentInvoiceId = $id;

    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatus($status) {
        $this->status = $status;
        $this->resetPage();
    }

    public function loadInvoice($id) {
        $this->dispatch('load-invoice',$id);
    }

    public function getOrder($id = null) {
        //$order = Order::find($id);
        //$this->order = $order;
        $this->dispatch('current-order',$id);
    }

    #[On('display-message')]
    public function displayMessage($msg) {

        if (is_array($msg)) {
            if (isset($msg['msg']))
                LivewireAlert::title($msg['msg'])->success()->position(Position::TopEnd)->toast()->show();

            if (!isset($msg['hide'])) $msg['hide'] = 1;

            $this->dispatch('hide-slider',$msg['hide']);
        } elseif ($msg)
            LivewireAlert::title($msg)->success()->position(Position::TopEnd)->toast()->show();

    }

    public function sendEmail($ids) {
        $ids=explode(',',$ids);
        $filename=array();

        $orders=Order::wherein('id',$ids)->get();
        $printOrder = new \App\Libs\PrintOrder(); // Create Print Object

        foreach ($orders as $order) {
            $ret = $printOrder->print($order,'email'); // Print newly created proforma/order.

            //$arr=$this->print($id,'emailmultiple');

            $order=$ret[1];
            $filename[] = $ret[0];

            if ($order->email=='') {
                LivewireAlert::title("Email was not specified. Please enter email and try again!")->error()->toast()->show();
                return;
            }

            $order->emailed=1;
            $order->update();
        }

        LivewireAlert::title("Successfully emailed invoice!")->error()->toast()->show();
        // request()->session()->flash('message', "Successfully emailed invoice!");
    }

    public function createNew() {
        $this->dispatch('create-new');
    }

    public function removeInvoice($id)
    {
        $order = Order::find($id);
        $product_ids = array();
        foreach ($order->products as $product) {
            if ($product->p_status != 4 && $product->category_id!=74) {
                if ($order->method != "On Memo") {
                    $product->p_qty = $product->p_qty + $product->pivot->qty;
                    $product->p_status = 0;
                    $product->update();
                }

            }
        }

        foreach ($order->products() as $product) {
            $product->qty = 0;
            $product->update();
        }

        $payment = Payment::where('order_id',$id);
        $payment->delete();

        request()->session()->flash('message', "Successfully deleted invoice!");
    }

    public function deleteInvoice($id)
    {
        $order = Order::find($id);
        // $product_ids = array();
        foreach ($order->products as $product) {
            if ($product->p_status != 4 && $product->category_id!=74) {
                if ($order->method != "On Memo") {
                    $product->p_qty = $product->p_qty + $product->pivot->qty;
                    $product->p_status = 0;
                    $product->update();
                }

            }
        }

        $order->products()->detach();
        $order->customers()->detach();

        $payment = Payment::where('order_id',$id);
        $payment->delete();

        $order->delete();

        request()->session()->flash('message', "Successfully deleted invoice!");
    }

    public function returnAllProducts($id) {
        $order = Order::find($id);
        if (isset($order->payments)) {
            if ($order->payments->count()) {
                $payment = $order->payments->sum('amount');

                $this->dispatch('itemMsg', 'A payment has already been applied in the amount of $' .number_format($payment,2) . '. If you want to modify the quantity or the amount,  you must delete the payment first and then try again.');
                return false;
            }
        }

        foreach ($order->products as $product) {
            if ($product->p_status != 4 && $product->category_id!=74) {
                if ($order->method != "On Memo")
                    $product->p_qty = $product->p_qty + $product->pivot->qty;

                $product->p_status = 0;
                $product->pivot->qty = 0;
                $product->pivot->update();
                $product->update();
            }
        }

        $order->subtotal = 0;
        $order->total = 0;
        $order->status = 2;
        $order->update();

    }

    public function render()
    {
        $totalCost = 0;

        $columns = ['orders.id','b_company','b_lastname','b_firstname', 's_company','method','product_name', 'serial'];
        $searchTerm = $this->generateSearchQuery($this->search, $columns);
        $status = $this->status;

        // select('orders.*') tells Laravel to hydrate Order models instead of a mixed collection of joined data.
        // Then with() will correctly load relationships like customers.
        $orderQuery = Order::select('orders.*')
                ->with(['customers', 'payments', 'products'])
                ->join('order_product', 'order_product.order_id', '=', 'orders.id')
                ->when(strlen($searchTerm) > 0, function ($query) use ($searchTerm) {
                    $query->where(function ($q) use ($searchTerm) {
                        // Use the raw search term (for the `orders` table)
                        $q->whereRaw($searchTerm);
                    });
            })
            ->when($status < 4, function ($query) use ($status) {
                $query->where('orders.status', $status);
            })
            ->distinct() // If the join causes duplicate orders due to multiple matching products
            ->orderBy('orders.id', 'desc');

        if ($this->status != 1)
            $totalCost = $orderQuery->sum('total');

        // $orders = $orders->paginate(perPage: 10);
        if ($this->status != 1) {
            foreach ($orderQuery->get() as $order) {
                if ($order->payments) {
                    $totalCost -= $order->payments->sum('amount');
                }


                if ($order->orderReturns->count()) {
                    foreach ($order->orderReturns as $returns)
                        $totalCost -= ($returns->pivot->amount*$returns->pivot->qty);
                }
            }
        }

        $total = $orderQuery->getQuery()->distinct('orders.id')->count('orders.id');
        $orders = $orderQuery->paginate(10, ['*'], 'page', null)->withPath('')->appends(request()->query());

        return view('livewire.invoices',["orders"=>$orders, 'totalcost' => $totalCost])
            ->layoutData(['pageName' => 'Invoices'])
            ->title("Invoices");

    }
}
