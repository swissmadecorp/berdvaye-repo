<?php

namespace App\Livewire;

use Imagick;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\ProductRetail;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use App\Jobs\AIProductDescription;
use Livewire\Attributes\Computed;

class ProductItem extends Component
{
    public $item;
    public $status;
    public $created_date;
    public int $productId = 0;
    public $model = '';
    public $product = null;
    public int $totalorders = 0;
    public int $perPage = 10;
    public $orders;

    public function clearFields() {

        $columns = $this->clearAllFields();

        $this->productId = 0;
        $this->totalorders = 0;
        $this->status = -1;

        // $this->item = null;
        // $this->reset('item','is_duplicate','images','newprice','status');
        $this->resetValidation();
        $this->reset($columns);

    }

    private function clearAllFields() {
        $columns = ['item.id','item.p_model','item.p_serial','item.p_comments','item.image','item.model_name','item.p_qty','item.retail'];

        return $columns;
    }

    protected function rules() {
        return [
            'item.p_model' => ['required'],
            'item.p_qty' => ['required'],
            'item.p_serial' => [
                'required',
                Rule::unique('products', 'p_serial')->where(function ($query) {
                    return $query
                        ->where('p_model', $this->item['p_model']);
                })->ignore($this->productId),
            ],
        ];
    }

    protected $messages = [
        'item.p_model.required' => 'This field is required.',
        'item.p_serial.required' => 'This field is required.',
        'item.p_serial.unique' => 'The serial number must be a unique number.',
        'item.p_qty.required' => 'This field is required.',
    ];

    public function saveProduct() {

        $validatedData = $this->validate();

        $id = $this->save();
        $this->dispatch('display-message',['msg'=>'Product Saved.','id'=>$id]);

        $this->clearFields();
    }



    public function save() {

        // $this->item['slug']=$this->createSlug();
        $dataToSave = Arr::except($this->item, ['retail', 'price', 'heighest_serial', 'model_name','image']);

        if ($this->productId) {
            $product = Product::find($this->productId);
            $dataToSave['p_status'] = $this->status;

            $product->update($dataToSave);
        } else {
            $product = Product::create($this->item);
        }

        return $product->id;
    }

    public function selectedStatus() {
        return $this->status;
    }

    public function updated($e,$props) {
        if ($e == 'status') {
            if ($this->status == 3 || $this->status == 4) {
                $this->item['p_qty'] = 0;
            } elseif ($this->status < 3 || $this->status == 5)
                $this->item['p_qty'] = 1;
        }
    }

    public function setNewModelNumber($value) {

        if (!empty($value['data'])) {
            $productRetail = ProductRetail::find($value['data']);

            $retail = $productRetail->p_retail;
            $this->model = strtoupper($productRetail->p_model);
            $reference = $productRetail->model_name;

            if ($productRetail->image_location)
                $imageElem = "/images/gallery/thumbnail/" . strtolower($productRetail->p_model) ."_thumb.jpg";
            else
                $imageElem = '/images/no-image.jpg';

            $this->item = [
                'product_retail_id'=>$productRetail->id,
                'image' => $imageElem,
                'p_serial' => isset($this->item['p_serial']) ? $this->item['p_serial'] : '',
                'model_name' => $reference,
                'p_model' => $this->model,
                'p_qty'=>1,
                'heighest_serial' => $productRetail->heighest_serial,
                'price'=>$productRetail->p_retail / 2,
                'retail'=>$productRetail->p_retail,
            ];

            $this->status = 0;
            // $this->dispatch('newProductData', $item);
        }
    }

    #[On('edit-item')]
    public function editItem($id) {

        if ($id) {
            $product = Product::find($id);

            $this->item = $product->toArray();

            $this->productId = $id;
            $this->model = $product->p_model;

            $this->status = $product->p_status;
            if ($id != 1) {
                $this->totalorders = $product->orders->count();
                $this->orders = $product->orders;
            } else {
                $this->orders = null;
                $this->totalorders = 0;
            }
            $this->item['retail'] = number_format($product->retail->p_retail,2);
            $this->created_date = $product->created_at;
            $this->item['image'] = $product->image();
            $this->item['model_name'] = $product->retail->model_name;
            $this->product = $product;
        }
    }

    public function mount() {
        $this->item['p_qty'] = 1;
    }

    public function render()
    {
        return view('livewire.product-item');
    }
}
