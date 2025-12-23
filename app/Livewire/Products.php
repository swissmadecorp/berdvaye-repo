<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\GlobalPrices;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Product;
use App\Jobs\AutomateEbayPost;
use App\Models\TheShow;
use App\Models\EbayListing;
use App\Models\User;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Jantinnerezo\LivewireAlert\Enums\Position;
use App\Libs\SearchCriteriaTrait;

class Products extends Component
{
    use WithPagination,  SearchCriteriaTrait;
    protected $paginationTheme = 'tailwind';

    #[Url(keep: true)]
    public $search = "";

    public $checked = false;
    public $productSelections = [];
    public $sortDirection = "DESC";
    public $sortBy = "products.id";
    public $onhand = 1;
    public $status = 0;
    public $editProductID = null;
    public $exportSelections = [1,0,0,0];
    public $selectAll = false;
    public $sproducts;

    #[Validate('required|min:1|max:3')]
    public $productQty = null;

    #[Validate('required|min:1')]
    public $productDealerPrice = null;

    public $productFieldName = null;
    public $page = 1;

    protected $queryString = [
        'search',
        'page',
        'onhand',
        'status',
    ];

    public function setProductId($id) {
        $this->dispatch('current-productid',$id);
    }

    public function makeInvoice($id) {
        $build = [];
        if (!empty($this->productSelections)) {
            $build = $this->productsSelected();
            $this->productSelections = [];
        } else {
            $build[] = $id;
        }

        $this->dispatch('create-invoice', ids: $build, page: 'products');
    }

    public function deleteProduct($id) {

        if (! Auth()->user()->hasRole('administrator')) {
            abort(403);
        }
        $product = Product::find($id);
        $product->delete();

        //$username=\Auth::user()->username;
        LivewireAlert::title("Product #$id has been deleted successfully.")->success()->position(Position::TopEnd)->toast()->show();
    }

    #[On('display-message')]
    public function displayMessage($msg) {
        if (is_array($msg)) {
            // request()->session()->flash('message', $msg['msg']);
            if (isset($msg['msg']))
                LivewireAlert::title($msg['msg'])->success()->position(Position::TopEnd)->toast()->show();

            if (!isset($msg['hide'])) $msg['hide'] = 1;

            $this->dispatch('hide-slider',$msg['hide']);
        } elseif ($msg)
            LivewireAlert::title($msg)->success()->position(Position::TopEnd)->toast()->show();

        // $this->dispatch('process-product-item-messages',$msg);
    }

    public function cancelEdit() {
        $this->reset('editProductID','productQty','productDealerPrice','productFieldName');
    }

    public function createInvoice($id) {

        if (!empty($this->productSelections)) {
            $build = '';
            $productArray = (array_keys($this->productSelections));
            foreach ($productArray as $key) {
                if ($this->productSelections[$key] == true) {
                    if (!$build)
                        $build = "?id[]=" . $key;
                    else $build .= "&id[]=" . $key;
                }
            }

            $this->reset('productSelections');
            if ($build)
                return redirect()->to("/admin/orders/create$build");
            else redirect()->to("/admin/orders/create?id=$id");
        } else {
            return redirect()->to("/admin/orders/create?id=$id");
        }
    }

    public function redirectToProductPage($id) {

        return redirect()->to("/admin/products/$id/edit");
    }

    public function editItem($id) {
        $this->dispatch('edit-item',$id);
    }

    public function editMode($id, $fieldName) {
        $this->editProductID = $id;
        switch ($fieldName) {
            case "qty":
                $this->productQty = Product::findOrFail($id)->p_qty;
                break;
            case "dealerPrice":
                $product = Product::findOrFail($id);
                if ($product) {

                    $this->productDealerPrice=number_format($product->p_newprice,0,"","");
                }
                break;
        }

        $this->productFieldName = $id.'.'.$fieldName;
    }

    public function doSort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection == "ASC" ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $column;
        $this->sortDirection = "DESC";
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus(){
        $this->updatingSearch();
    }

    public function render()
    {
        // To add a textbox with a length counter just create 2 spans inside the inputbox
        // and within the 1st span add class="absolute -ml-6 mt-2" and
        //  within the 2nd span add x-text="wire.name.length"
        $columns = ['p_serial','products.id','products.p_model','model_name'];
        $searchTerm = $this->generateSearchQuery($this->search, $columns);

        if ($this->onhand==1)
            $sign = ">=";
        else $sign = "<=";

        $status = $this->status;
        $products = Product::join('product_retails','product_retails.id','=','product_retail_id')
            ->when(strlen($searchTerm)>0, function($query) use ($searchTerm) {
                $query->whereRaw($searchTerm);
            })->when($status > 0, function($query) use ($status) {
                $query->where('p_status',$status);
            })

            ->withSum('retail','p_retail')
            ->where('p_qty', $sign , $this->onhand)
            ->orderBy($this->sortBy, $this->sortDirection);


        $totalQty = $products->sum('p_qty');

        $this->sproducts = $products->get();
        $totalCost = $this->sproducts->sum('retail_sum_p_retail');

        $products = $products->paginate(perPage: 10);

        return view('livewire.products',["products"=>$products, 'totalCost' => $totalCost, 'totalQty' => $totalQty, 'pageName' => "Products"])
            ->layoutData(['pageName' => 'Products'])
            ->title("products");
    }

}
