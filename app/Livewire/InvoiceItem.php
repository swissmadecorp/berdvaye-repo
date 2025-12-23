<?php

// BerdVaye InvoiceItem
namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\Country;
use App\Models\State;
use App\Models\Customer;
use App\Models\TheShow;
use App\Models\Product;
use App\Models\Taxable;
use App\Jobs\eBayEndItem;
use App\Models\Payment;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\Credit;
use App\Models\Returns;
use App\Models\OrderReturn;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Jantinnerezo\LivewireAlert\Enums\Position;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class InvoiceItem extends Component
{
    use WithPagination;

    public $selectedBCountry;
    public $selectedSCountry;
    public $selectedBState;
    public $selectedSState;

    public $isOrderPage;
    public int $invoiceId = 0;
    public int $customerId = 0;
    public $customer = [];
    public $bstates = [];
    public $sstates = [];
    public $purchasedFrom = [];
    public $customerGroup = [];
    public int $customerGroupId = 0;
    public $totalPrice = 0;
    public $grandtotal = 0;
    public $items;
    public $invoice;
    public $removedItems = [];
    public $fromPage = "Invoice";
    public $memoTransfer = false;
    public $invoiceName;
    public $totalProfit = 0;
    public $returnAmount = 0;
    public $creditedItems = [];
    public $creditAmount = 0;
    public $totalLeft = 0;
    public $isCreditApplied = false;

    public $newProductId;
    public $newQty;
    public $newOnHand;
    public $newSerial;
    public $newProductName;
    public $newPrice;
    public $newCost;
    public $newImage;
    public $hideSlider;
    public $perPage = 10;

    public $paymentAmount;
    public $paymentRef;

    protected $oldItemValue;

    #[On('create-new')]
    public function createNew() {
        $this->selectedBCountry = 231;
        $this->selectedSCountry = 231;
        $this->selectedBState = 3956;
        $this->selectedSState = 3956;
    }

    #[On('load-invoice')]
    public function loadInvoice($id) {
        $this->invoiceId = $id;
        $this->invoice = Order::with('products')->find($id);

        $invoice=$this->invoice;
        $this->invoiceName = $invoice->method;

        $this->customerId = $invoice->customers->first()->id;

        if ($invoice) {
            $this->customer = $invoice->toArray();

            $this->selectedBCountry = $this->customer['b_country'];
            $this->selectedSCountry = $this->customer['s_country'];
            $this->selectedBState = $this->customer['b_state'];
            $this->selectedSState = $this->customer['s_state'];
            $this->customerGroupId = $invoice->customers->first()->cgroup;

            $this->customer['created_at'] = $invoice->created_at->format('m/d/Y');
            $this->customer['cc_status'] = $invoice->cc_status;

            // $this->removeItem("");
            foreach ($invoice->products as $product) {
                // $p_image = $product->images->toArray();

                // if (!empty($p_image)) {
                //     $image=$p_image[0]['location'];
                // } else $image = '../no-image.jpg';

                if ($product->retail->image_location)
                    $image = "/images/gallery/thumbnail/" . strtolower($product->retail->p_model) ."_thumb.jpg";
                else {
                    if ($product->image())
                        $image =  "/images/gallery/thumbnail/" . strtolower($product->retail->p_model) ."_thumb.jpg";
                    else $image = '/images/no-image.jpg';
                }

                $isReturned = '';
                if ($product->returns) {
                    $isReturned = $product->returns->where('order_id', $invoice->id)->first() ? "Returned" : "";
                }

                $cost = isset($product->pivot->retail) ? $product->pivot->retail : $product->retailvalue();

                $item = ['op_id'=>$product->pivot->id,'id'=>$product->pivot->product_id,'image'=>$image,
                    'product_name'=> !$product->pivot->product_name ? $product->p_model : $product->pivot->product_name,
                    'qty'=>$product->pivot->qty,'price'=>$product->pivot->price,
                    'msg'=>'','cost'=>$cost,'isReturned' =>  $isReturned,
                    'serial'=>$product->pivot->serial];

                //$this->totalProfit += $product->pivot->price-$cost;

                $this->addItem($item);
            }


            $this->returnAmount = 0;
            if ($invoice->orderReturns->count()) {
                foreach ($invoice->orderReturns as $returns)
                    $this->returnAmount -= ($returns->pivot->amount*$returns->pivot->qty);
            }

            $credit = Credit::where('customer_id',$this->customerId)->orderBy('id','desc')->first();
            if ($credit) {
                $this->creditAmount = $credit->amount;
            }
            else $this->creditAmount = 0;

            $this->calculateTotalPrice();
            $this->calculateTotalOwed();
        }
    }

    public function calculateTotalOwed() {
        $value = preg_replace('/[\$,]/', '', $this->grandtotal);
        $total = floatval($value);

        $payments = 0;
        if (isset($this->invoice->payments)) {
            if ($this->invoice->payments->count()) {
                $payments = $this->invoice->payments->sum('amount');
            }
        }

        $this->totalLeft = $total - $payments;

        if ($this->isCreditApplied) {
            $credit = Credit::where('customer_id',$this->customerId)->orderBy('id','desc')->first();

            if ($credit) {
                $newAmount = $credit->amount - $this->paymentAmount;
                if ($newAmount <= 0) {
                    $credit->delete();
                } else {
                    $credit->update([
                        'amount' => $newAmount
                    ]);
                }
                $this->creditAmount = $newAmount;
            }
        }
    }

    public function paginateItems()
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemsForCurrentPage = $this->items->slice(($currentPage - 1) * $this->perPage, $this->perPage)->values();

        return new LengthAwarePaginator(
            $itemsForCurrentPage,
            $this->items->count(),
            $this->perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    public function setCreditAmountAndRef() {
        if ($this->creditAmount > $this->totalLeft) {
            $this->paymentAmount = $this->totalLeft;
        } else $this->paymentAmount = $this->creditAmount;


        $this->isCreditApplied = true;
        $this->paymentRef = 'from credit';
    }

    public function savePayment($totalLeft) {
        $validatedData = $this->validate([
            'paymentAmount' => 'required',
            'paymentRef' => 'required|string',
        ], [
            'paymentAmount.required' => 'Payment Amount is required.',
            'paymentRef.required' => 'Payment Reference is required.',
        ]);

        if ($this->paymentAmount > $totalLeft)
            $applyAmount = $totalLeft;
        else $applyAmount = $this->paymentAmount;

        $orderId = $this->invoice->id;

        Payment::create ([
            'amount' => $applyAmount,
            'ref' => $this->paymentRef,
            'order_id' => $orderId
        ]);

        if ($this->invoice->payments->sum('amount') == $this->invoice->total) {
            $this->invoice->status = 1;
            $this->invoice->update();
        }

        $this->calculateTotalOwed();

        $this->reset('paymentAmount','paymentRef','isCreditApplied');
        // $this->clearFields();
        $this->dispatch('display-message',['msg'=>'Successfully applied the payment!','hide' => 0]); // false mean don't close the window

    }

    #[On('hide-slider')]
    public function hideslider($hide=1) {
        $this->hideSlider = $hide;
    }

    public function deletePayment($id) {
        $payment = Payment::find($id);
        $payment->delete();

        Order::find($payment->order_id)->update(['status' => 0]);

        $this->calculateTotalOwed();
        // $this->clearFields();
        $this->dispatch('display-message',['msg'=>'Payment has been successfully deleted!','hide' => 0]); // false mean don't close the window
    }

    private function removeFromInventoryAdjuster($id) {
        $inventory = \DB::table('table_temp_a')->where('id',$id);

        if(count($inventory->get())){
            $product=Product::join('table_temp_a','table_temp_a.id','=','products.id')
            ->where('table_temp_a.id',$id)->first();

            $inventory->delete();
        }
    }

    public function TransferToInvoice() {
        $this->memoTransfer = true;
        $this->customer['method'] = "Invoice";
        $this->customer['po'] = "FROM MEMO";

        $this->saveInvoice();
        // $this->clearFields();
    }

    public function updateItemImage($id,$newImage) {
        $this->items = $this->items->map(function ($item) use ($id, $newImage) {
            if (isset($item['id']) && $item['id'] === $id) {
                $item['image'] = $newImage;
            }
            return $item;
        });
    }

    public function saveInvoice() {

        // if (\Auth::user()->name == 'Edward B' && $this->invoiceId) {

        // }
        if (count($this->items)==0) {
            $this->dispatch('itemMsg', 'You did not select any products. Please add at least one item to this invoice.');
            $validatedData = $this->validate(
                $this->rules(),
                $this->messages
            );
        } else {
            $this->resetValidation();

            foreach ($this->items as $index => $item) {
                if ($item['id'] && !$item['price']) {
                    $this->dispatch('itemMsg', "One or more items don't have a price set.");
                    $this->addError("items.{$index}.price",'Price cannot be empty');
                    return;
                }
            }

            $validatedData = $this->validate(
                $this->rules(),
                $this->messages
            );

            $this->customer['cgroup'] = $this->customerGroupId;

            $created_at = isset($this->customer['created_at']) ? $this->customer['created_at'] : '';

            if ($created_at) {
                $this->customer['created_at']=date('Y-m-d H:i:s', strtotime($created_at));
                $this->customer['updated_at']=date('Y-m-d H:i:s', strtotime($created_at));
            }

            $customer = Customer::find($this->customerId);

            $this->customer['b_country'] = $this->selectedBCountry;
            $this->customer['s_country'] = $this->selectedSCountry;

            $this->customer['b_state'] = $this->selectedBState;
            $this->customer['s_state'] = $this->selectedSState;

            $data = array(
                'cgroup' => $this->customerGroupId,
                'firstname' => isset($this->customer['b_firstname']) ? $this->customer['b_firstname'] : "",
                'lastname' => isset($this->customer['b_lastname']) ? $this->customer['b_lastname'] : "",
                'company' => isset($this->customer['b_company']) ? $this->customer['b_company'] : "",
                'address1' => isset($this->customer['b_address1']) ? $this->customer['b_address1'] : "",
                'address2' => isset($this->customer['b_address2']) ? $this->customer['b_address2'] : "",
                'phone' => isset($this->customer['b_phone']) ? localize_us_number($this->customer['b_phone']) : "",
                'country' => $this->customer['b_country'],
                'state' => $this->customer['b_state'],
                'city' => isset($this->customer['b_city']) ? strtoupper($this->customer['b_city']) : "",
                'zip' => isset($this->customer['b_zip']) ? $this->customer['b_zip'] : ""
            );


            $customer = Customer::updateOrCreate(['company'=>$this->customer['b_company']],$data);

            if ($this->invoiceId && !$this->memoTransfer) {
                $this->customer['status']= $this->invoice->status;
                if ($customer->id != $this->customerId) {
                    $this->invoice->customers()->detach();
                    $this->invoice->customers()->attach($customer->id);
                }
                $this->invoice->update($this->customer);
                $order = $this->invoice;
            } else {
                $this->customer['status'] = 0;
                $this->customer['payment_options'] = 'Due upon receipt';

                $order = Order::create($this->customer);
                $order->customers()->attach($customer->id);
            }

            $product_ids=array();

            foreach ($this->items as $index => $item) {
                $product_id = $item['id'];
                $img_name = null;

                if ($item['id']) { // if $item has an id then we have a product in the array

                    $qty = $item['qty'];
                    $price = $item['price'];
                    $product_name = $item['product_name'];
                    $retail = $item['cost'];

                    if (!$product_name)
                        $product_name = "Miscellaneous";

                    $product = Product::where('id',$product_id)->first();
                    // $retail = $product->retail->p_retail;

                    if (!$item['op_id'] || $this->memoTransfer) {
                        $isReturned = null;
                        if ($this->memoTransfer && $this->invoiceId )
                            $isReturned = $product->returns->where('order_id', $this->invoiceId)->first();

                        if (!$isReturned) {
                            $product_ids[]=$product_id;
                            $serial = isset($item['serial']) ? $item['serial'] : "";
                            if ($item['image']) {
                                $path_parts = pathinfo($item['image']);
                                $img_name = $path_parts['filename'].'.'.$path_parts['extension'];
                            }

                            $product_array = [
                                'qty' => $qty,
                                'price' => $price,
                                'img_name' => $img_name,
                                'serial' => $serial,
                                'product_name' => $product_name,
                                'retail' => $retail
                            ];

                            $order->products()->attach($product->id, $product_array);

                            if ($this->customer['method'] == 'Invoice') {
                                if ($product_id != 491) {
                                    $product->p_status=8; // mark as sold
                                    $product->decrement('p_qty');
                                    $product->update();
                                }
                            } elseif ($this->customer['method'] == 'On Memo') {
                                $product->p_status=1;
                                $product->update();
                            }
                        }
                    } else {
                        $op_id = $item['op_id'];
                        if ($item['image']) {
                            $path_parts = pathinfo($item['image']);
                            $img_name = $path_parts['filename'].'.'.$path_parts['extension'];
                        }

                        $serial = isset($item['serial']) ? $item['serial'] : "";
                        \DB::table('order_product')
                            ->where('id', $op_id)
                            ->update([
                                'qty' => $qty,
                                'price' => $price,
                                'retail' => $retail == 0 ? $product->retailvalue() : $retail,
                                'serial' => $serial,
                                'product_name' => $product_name,
                                'img_name' => $img_name
                            ]);

                        $method = $this->customer['method'];
                        $returnBackToInvoiceItems = [];
                        $removedItems = [];

                        if ($this->invoice->status == 1) { // sold page
                            $product->p_status=0;
                            if ($qty == 0 && $method == "Invoice") {
                                $removedItems[] = $product_id;
                                if ($qty == 0)
                                    $product->increment('p_qty');
                            }
                            $product->update();
                        } elseif ($this->invoice->status == 0) { // open invoice/memo
                            if ($qty == 0) {
                                if ($method == "Invoice") {
                                    $removedItems[] = $product_id;
                                    $product->p_status=0;
                                    if ($qty == 0)
                                        $product->increment('p_qty');
                                }
                            }
                            $product->update();
                        } elseif ($this->invoice->status == 3) { // returned
                            if ($qty == 1) {
                                if ($method == "Invoice") {
                                    $returnBackToInvoiceItems[] = $product_id;

                                    $product->p_status=1;
                                    $product->decrement('p_qty');
                                }
                            }
                            $product->update();
                        }

                        if (!empty($returnBackToInvoiceItems))
                            $this->returnBackToInvoice($returnBackToInvoiceItems);

                        if (!empty($this->creditedItems))
                            $this->returnPaidBackToStock();

                        $this->returnSelectedItems($removedItems, $returnBackToInvoiceItems);

                    }
                }
            }

            // If there is only 1 or item's quantity were set to 0 item in the collection, that means there are no items left
            if (count($this->items) == 0 || $this->allItemsQuantityZero()) {
                $order->status = 3;  // Mark order status as returned
            } elseif ($this->allItemsQuantityZero()==false)
                $order->status = 0;

            $freight = 0;
            if (isset($this->customer['freight']) && $this->customer['freight'] != '')
                $freight = $this->customer['freight'];

            if ($this->customerGroupId == 1) {
                $tax = $this->customer['tax']; //Taxable::where('state_id',$order->s_state)->value('tax');
                $total = number_format($this->totalPrice + ($this->totalPrice * ($tax/100))+$freight,2, '.', '');
            } else {
                $tax = 0;
                $total = $this->totalPrice+$freight;
            }

            // dd($order->payments->sum('amount') , ' ', $total);
            if ($order->payments->sum('amount') == $total && $order->payments->sum('amount') > 0)
                $status = 1;
            else $status = $order->status;

            $order->update([
                'subtotal' => $this->totalPrice,
                'total' => $total,
                'taxable' => $tax,
                'freight' => $freight,
                'status' => $status
            ]);

            // eBayEndItem::dispatch($product_ids);

            if ($this->fromPage == 'products')
                $this->invoiceId = $order->id;

            $this->clearFields();

            $this->dispatch('display-message','Invoice/Memo Saved.');

        }
    }

    protected function returnBackToInvoice($returnBackToInvoiceItems) {
        $return = OrderReturn::where('order_id', $this->invoiceId)->get();
        $invoice = $this->invoice;

        if ($return) {
            //$return->where('product_id',$item)->first()->delete();

            foreach ($returnBackToInvoiceItems as $id) {

                $product = $invoice->products->find($id);
                $amount=($product->pivot->price);

                if (isset($this->invoice->customers->first()->credit)) {
                    $credit = $this->invoice->customers->first()->credit->amount;
                    $credit = $credit - $product->pivot->price;
                    if (!$credit) {
                        $this->invoice->customers->first()->credit->delete();
                    } else {
                        $this->invoice->customers->first()->credit->update([
                            'amount' => $credit
                        ]);
                    }
                }
            }
        }
    }

    protected function returnPaidBackToStock() {

        $invoice = $this->invoice;

        if ($invoice->status == 1) {
            foreach ($this->creditedItems as $i => $item) {
                $product_id = $item;
                $product = $invoice->products->find($product_id)->first();

                $amount=($product->pivot->price);
                $customer_id=$invoice->customers()->first()->id;
                $credit = Credit::where('customer_id',$customer_id)
                    ->whereNull('amount')
                    ->orderBy('id','desc');

                if (!$credit->first()) {
                    Credit::create([
                        'customer_id' => $customer_id,
                        'amount' => $amount, //-$amountOwed
                        'ref' => 'return from #'.$invoice->id
                    ]);

                } else {
                    $credit->update([
                        'amount' => $credit->amount+$amount//-$amountOwed
                    ]);
                }
            }
        }
    }

    protected function returnSelectedItems($removedItems, $returnBackToInvoiceItems = []) {

        $return = OrderReturn::where('order_id', $this->invoiceId)->first();
        $totalAmount = 0; //

        $invoice = $this->invoice;

        if (!$return) {
            $return = Returns::create ([
                'comment' => null
            ]);
            $total = $this->invoice->subtotal;
        } else {
            $return=Returns::find($return->returns_id);

            $total = $invoice->returns->sum('pivot.amount');
            $total = $invoice->subtotal-$total;
        }

        if (!empty($removedItems)) {
            foreach ($removedItems as $i => $item) {
                $product_id = $item;
                $product = $invoice->products->find($product_id);

                $amount=($product->pivot->price*$product->pivot->qty);
                $totalAmount += $amount;
                $total -= ($product->pivot->price*$product->pivot->qty);

                $invoice->returns()->attach($return,[
                    'product_id' => $product_id,
                    'order_id' => $this->invoiceId,
                    'amount' => $amount,
                    'qty' => 1,
                ]);

                // dd($invoice->returns());
                $product->update([
                    'p_qty' => 1,
                    'p_status' => 0
                ]);

            }
        } elseif (!empty($returnBackToInvoiceItems)) {
            $invoice->returns()->detach($return);
            $return->delete();
        }

    }

    private function allItemsQuantityZero() {
        return $this->items->every(function ($item) {
            return empty($item['qty']) || $item['qty'] == 0;
        });
    }

    #[On('create-invoice')]
    public function createInvoice($ids,$page) {
        $this->fromPage = $page;

        $this->selectedBCountry = 231;
        $this->selectedSCountry = 231;
        $this->selectedBState = 3956;
        $this->selectedSState = 3956;

        foreach ($ids as $id) {
            $product = Product::find($id);
            $p_image = $product->images->toArray();

            if (!empty($p_image)) {
                $image=$p_image[0]['location'];
            } else $image = '../no-image.jpg';

            $item = ['op_id'=>0,'id'=>$product->id,'image'=>$product->image(),
                'product_name'=>$product->retail->model_name, 'qty'=>1,'price'=>$product->retail->p_retail / 2,
                'onhand'=>$product->p_qty,'msg'=>'','cost'=>number_format($product->retail->p_retail,0,'',''),'serial'=>$product->p_serial];

            $this->addItem($item);
        }

    }

    public function setCustomerInfo($value) {
        if ($value['data']) {
            $customer = Customer::find($value['data']);
            $this->customer['b_firstname'] = $customer->firstname;
            $this->customer['b_lastname'] = $customer->lastname;
            $this->customer['b_company'] = $customer->company;
            $this->customer['b_address1'] = $customer->address1;
            $this->customer['b_address2'] = $customer->address2;
            $this->customer['b_phone'] = $customer->phone;
            $this->customer['b_country'] = $customer->country;
            $this->customer['b_state'] = $customer->state;
            $this->customer['b_city'] = $customer->city;

            // dd($this->customer['b_country']);
            $this->customer['b_zip'] = $customer->zip;
            $this->customer['email'] = $customer->email;

            $this->customerId = $customer->id;
            $this->customer['s_firstname'] = $customer->firstname;
            $this->customer['s_lastname'] = $customer->lastname;
            $this->customer['s_company'] = $customer->company;
            $this->customer['s_address1'] = $customer->address1;
            $this->customer['s_address2'] = $customer->address2;
            $this->customer['s_phone'] = $customer->phone;
            $this->customer['s_country'] = $customer->country;
            $this->customer['s_state'] = $customer->state;
            $this->customer['s_city'] = $customer->city;
            $this->customer['s_zip'] = $customer->zip;

            $credit = Credit::where('customer_id',$customer->id)->orderBy('id','desc')->first();
            if ($credit)
                $this->creditAmount = $credit->amount;
            else $this->creditAmount = 0;
        }
    }

    public function clearFields() {
        $this->resetValidation();
        $this->reset();

        $this->purchasedFrom = [1=>'Swiss Made',2=>'Signature Time'];
        $this->customerGroup = ['Dealer','Customer'];

        // Clear all items in the collection
        $this->selectedBCountry = 0;
        $this->selectedSCountry = 0;
        $this->selectedBState = 0;
        $this->selectedSState = 0;

        $this->initItems();
    }

    public function initItems() {
        // $this->fill([
        //     'items' =>collect([['op_id'=>0,'id'=>'','img'=>'','product_name'=>'', 'price'=>'','qty'=>'','onhand'=>'','msg'=>'','cost'=>'','serial'=>'']])
        // ]);

        $this->items = collect([]);
    }

    #[Computed]
    public function productImages() {
        $directory = base_path().'/public/images/gallery/thumbnail';

        if (!is_dir($directory)) {
            exit('Invalid diretory path');
        }

        $files = array();
        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = "<li class='cursor-pointer ellipsis flex items-center p-1 border-b h-25'><img class='w-16' src='/images/gallery/thumbnail/$file' /><span class='p-1'>$file</span></li>";
            }
        }

        return $files;
    }

    #[Computed]
    public function countries() {
        return Country::All();
    }

    #[Computed]
    public function billingStates() {
        return State::where('country_id',$this->selectedBCountry)->get();
    }

    #[Computed]
    public function shippingStates() {
        return State::where('country_id',$this->selectedSCountry)->get();
    }

    public function mount() {

        $this->purchasedFrom = ['Swiss Made','Signature Time'];
        $this->customerGroup = ['Dealer','Customer'];

        $states = $this->shippingStates;

        // If `selectedSState` is not valid or not set, set it to the first valid state
        if (!$this->selectedSState || !in_array($this->selectedSState, $states->pluck('id')->toArray())) {
            $this->selectedSState = $states->first()?->id;
        }

        $this->initItems();
    }

    protected function rules() {
        return [
            'customer.method' => ['required','not_in:-1'],
            'customer.b_company' => ['required'],
            'items' => 'required|array|min:1',
            //'items.*.price' => 'required'
        ];
    }

    protected $messages = [
        'customer.method.required' =>'This field is required.',
        'customer.method.not_in' => 'The selected payment method is invalid.',
        'customer.b_company.required' => 'This field is required.',
        'items.required' => 'At least one row must be filled in.',
        'items.min' => 'At least one row must be filled in.',
        //'items.*.price.required' => 'Price cannot be empty.',
    ];

    protected function calculateTotalPrice($calc = 1, $returnAmount = 0) {

        if ($calc) {
            $this->totalProfit = 0;
            $this->totalPrice = $this->items
                ->filter(function ($item) {
                    return !empty($item['id']) || $this->items->last() !== $item;
                })
                ->sum(function ($item) {
                    if (!$item['qty'])
                        $qty = 0;
                    else $qty = $item['qty'];

                    $price = preg_replace('/[\$,]/', '', $item['price']);

                    if ($price)
                        $p = ($price*$qty);
                    else $p=0;

                    $c = $item['cost']*$qty;

                    $this->totalProfit += $p - $c;

                    return $p && $qty ? $price * $qty : 0;
                });
                $this->totalProfit = '$'.number_format($this->totalProfit,2);
        }

        $freight = 0;
        $tax = 0;
        $discount = 0;

        if (isset($this->customer['freight']) && $this->customer['freight'])
            $freight = $this->customer['freight'];

        if ($this->selectedSState == 3956 && $this->customerGroupId == 1) {
            $tax = Taxable::where('state_id',$this->selectedSState)->value('tax');
            $this->customer['tax'] = $tax;
        } else $this->customer['tax'] = null;

        if (isset($this->customer['discount']) && $this->customer['discount']) {
            $discount = $this->customer['discount'];
            // $this->totalPrice -= $discount;
        }



        if ($calc) {
            $total = ($this->totalPrice -$discount + $freight );
            $total = $total + ($total) * ($tax/100);

            if ($this->returnAmount)
                $total += $this->returnAmount;

            $this->grandtotal = '$'.number_format($total,2);
        } else {
            if (is_numeric($this->totalPrice)) {
                $total = ($this->totalPrice) / (1+($tax/100))+$discount - $freight;
                $this->totalPrice = $total;
            }
        }
    }

    public function returnItem($data) {
        $index = $data['id'];
        $item = $this->items->get($index);

        $item['qty'] = $this->invoice->products[$index]->pivot->qty;
        $item['price'] = $this->invoice->products[$index]->pivot->price;
        $this->items->put($index, $item);

        $this->calculateTotalPrice();
    }

    public function returnWithCredit($data) {
        $index = $data['id'];
        $item = $this->items->get($index);

        $this->creditedItems[] = $item;

    }

    public function updated($propertyName) {

        if (preg_match('/items\.(\d+)\.(\w+)/', $propertyName, $matches)) {
            // $index = (int) filter_var($propertyName, FILTER_SANITIZE_NUMBER_INT);
            $index = $matches[1];
            $property = $matches[2];

            if ($property == 'price' || $property == 'qty') {

                $this->calculateTotalPrice();

                if (isset($this->invoice->payments)) {
                    if ($this->invoice->payments->count()) {
                        $payment = $this->invoice->payments->sum('amount');
                        $value = preg_replace('/[\$,]/', '', $this->grandtotal);
                        $total = floatval($value);

                        if ($payment > $total) {

                            // $this->dispatch('itemMsg', 'A payment has already been applied in the amount of $' .number_format($payment,2) . '. If you want to modify the quantity or the amount,  you must delete the payment first and then try again.');
                            LivewireAlert::title('')
                                ->text('A payment has already been applied in the amount of $' .number_format($payment,2) . '. Would you like to return for credit?')
                                ->asConfirm()
                                ->withOptions([
                                    'background' => '#f0f0f0',
                                    'customClass' => [
                                        'popup' => 'animate__animated animate__bounceIn',
                                    ],
                                    'allowOutsideClick' => false,
                                ])
                                ->onConfirm('returnWithCredit', ['id' => $index])
                                ->onDeny('returnItem', ['id' => $index])
                                ->show();
                            // return false;
                        }
                    }
                }
            }
            // dd($this->items);
        } elseif ($propertyName == 'customer.freight' || $propertyName == 'customer.discount' || $propertyName == 'customer.cgroup' || $propertyName == 'customerGroupId') {
            $this->calculateTotalPrice();
        } elseif ($propertyName == 'selectedBCountry') {
            $this->selectedSCountry = $this->selectedBCountry;
            $this->selectedBState = null;
            $this->selectedSState = null;
        } elseif ($propertyName == 'selectedSCountry') {
            $this->selectedSState = null;
        } elseif ($propertyName == 'selectedBState') {
            $this->selectedSState = $this->selectedBState;
            $this->calculateTotalPrice();
        } elseif ($propertyName == 'customer.s_zip') {
            $szip = trim($this->customer['s_zip']);
            if (strlen($szip) == 5) {
                $address = addressFromZip($szip);

                $this->selectedSCountry = 231;
                $this->customer['s_city'] = $address['city'];
                $this->selectedSState = $address['state'];
            }
        } elseif ($propertyName == 'customer.b_zip') {
            $bzip = trim($this->customer['b_zip']);
            if (strlen($bzip) == 5) {
                $address = addressFromZip($bzip);

                $this->selectedBCountry = 231;
                $this->customer['b_city'] = $address['city'];
                $this->selectedBState = $address['state'];
            }

        } elseif ($propertyName == 'customer.s_firstname') {
            if (isset($this->customer['s_firstname'])) {
                $firstname = trim($this->customer['s_firstname']);
                if (strpos($firstname,' ') !== false) {
                    $firstname_lastname = explode(' ', $firstname);
                    $this->customer['s_firstname'] = $firstname_lastname[0];
                    $this->customer['s_lastname'] = $firstname_lastname[1];
                    $this->customer['s_company'] = $firstname;
                }
            }
        } elseif ($propertyName == 'grandtotal') {

            if ($this->totalPrice) {
                $value = preg_replace('/[\$,]/', '', $this->grandtotal);
                $value = floatval($value);
                $this->totalPrice = $value;
                $this->calculateTotalPrice(0);
            }

        }
    }

    public function setProductRow($value) {
        $this->newProductId = $value['data'];
        $this->addItem();
    }

    public function addItem($invoiceitem='') {
        if (!$invoiceitem) {
            $product = Product::find($this->newProductId);

            if ($product) {
                if ($this->newProductId==1)
                    $onhand = 1;
                else $onhand = $product->p_qty;

                if ($onhand) {
                    if ($product->image())
                        $image =  $product->image();
                    else $image = '/images/no-image.jpg';

                    $newItem = [
                        'op_id' => 0,
                        'id' => $this->newProductId,
                        'product_name' => $product->retail->model_name,
                        'price' => $product->retail->p_retail / 2,
                        'cost' => number_format($product->retail->p_retail,0,'',''),
                        'image' => $image,
                        'onhand' => $onhand,
                        'serial' => $product->p_serial,
                        'qty' => 1
                    ];


                    // Check for duplicates and allow duplicates for id 1
                    $info = $product->p_model . ' (' . $product->p_serial. ')';
                    if ($newItem['id'] != 491) {
                        $existingItem = $this->items->firstWhere('id', $newItem['id']);
                        if ($existingItem) {
                            // If an item with the same ID exists, replace it
                            $this->dispatch('itemMsg', $info. ' is already in the list.');
                            $this->items = $this->items->map(function ($item) use ($newItem) {
                                return $item['id'] == $newItem['id'] ? $newItem : $item;
                            });
                        } else {
                            $this->items->push($newItem);
                            $this->dispatch('itemadded', $this->newProductId);
                        }
                    } else {
                        $this->items->push($newItem);
                        $this->dispatch('itemadded', $this->newProductId);
                    }

                    if ($product->p_status == 2) { // On hold
                        $this->dispatch('itemMsg',$info. ' is on hold');
                    }
                } else {
                    // $this->dispatch('itemZero', id: $this->newProductId);
                    $this->dispatch('itemMsg', $info. ' is out of stock');
                }
            } else {
                $this->dispatch('itemMsg', $info. ' is not found in the inventory.');
            }
            $this->newProductId = '';
            $this->calculateTotalPrice();
        } else {
            if ($invoiceitem['id'] != 491) {
                $existingItem = $this->items->firstWhere('id', $invoiceitem['id']);
                if ($existingItem) {
                    // If an item with the same ID exists, replace it
                    $this->items = $this->items->map(function ($item) use ($invoiceitem) {
                        return $item['id'] == $invoiceitem['id'] ? $invoiceitem : $item;
                    });
                } else {
                    $this->items->push($invoiceitem);
                    $this->dispatch('itemadded', $this->newProductId);
                }
            } else {
                $this->items->push($invoiceitem);
                $this->dispatch('itemadded', $this->newProductId);
            }
        }

        // Force Livewire to re-render the component
        $this->render();
    }

    public function removeItem($data) {
        $index = 0;

        if (isset($data['index']))
            $index = $data['index'];

        if (!empty($data['arr']))
            $data = $data['arr'];
        $itemId = $data['id'];

        // store item with the op_id which is already in the database to be deleted later.
        $op_id = $data['op_id'];

        if (isset($op_id)) {
            $itemToRemove = $this->items->firstWhere('op_id', $op_id);
            if ($itemToRemove) {
                if ($itemToRemove['op_id'] != 0) {
                    $this->removedItems[] = $itemToRemove;
                }
            }
        }


        if ($itemId == 491) {
            $this->items = $this->items->filter(function ($item,$indx) use ($itemId, $index, $op_id) {
                if ($index == $indx) {
                    return false;
                    if ($op_id != 0) {
                        $this->removedItems[] = $item;
                    }
                }
                return true;
            })->values();
        } else {

            $this->items = $this->items->reject(function ($item) use ($itemId) {
                return $item['id'] == $itemId;
            })->values();
        }

        $this->calculateTotalPrice();
        $this->render();
    }

    public function removeSingleItemById($index) {

        $arr = $this->items->get($index);

        if ($arr['op_id'])
            LivewireAlert::title('')
                ->text('Are you sure you want to remove ' . $arr['product_name'] . ' ('. $arr['serial'] .') from invoice?')
                ->asConfirm()
                ->withOptions([
                    'background' => '#f0f0f0',
                    'customClass' => [
                        'popup' => 'animate__animated animate__bounceIn',
                    ],
                    'allowOutsideClick' => false,
                ])
                ->onConfirm('removeItem', ['arr' => $arr, 'index' => $index])
                ->show();
        else
            $this->removeItem($arr);
    }

    public function render()
    {
        return view('livewire.invoice-item', ['items' => $this->items, 'paginatedItems' => $this->paginateItems()]);
    }
}
