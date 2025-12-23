<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\ProductRetail;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use App\Services\CartService; // 1. Import the CartService

class ProductDetails extends Component
{

    public $slug = '';
    public $model = '';
    public $size = '';
    public $currentProduct = null;
    public $id = 0;
    public $isPageLoaded = false;
    public $banners = [];

    /**
     * Updates the current product based on the selected size.
     * The Livewire update automatically refreshes the component's view.
     * * @param string $size The newly selected size.
     */
    public function updateItem($size) {

        // 2. REMOVED: $this->isPageLoaded = true;
        // This line was removed because setting this state property here
        // conflicts with the permanent visual lock we implemented using Alpine.

        $product = ProductRetail::where('model_name',"LIKE",'%'.$this->currentProduct->model_name.'%')
            ->where('size',$size)
            ->first();

        $this->currentProduct = $product;
        $this->size = $product->size;
    }

    public function refreshCart() {
        $this->dispatch('refresh-cart');
    }

    /**
     * Handles adding the item to the cart using the CartService.
     * This replaces the old redirect-to-controller-route logic.
     * * @param string $model The product model to add.
     * @param CartService $cartService Automatically injected by Laravel/Livewire.
     */
    public function addToCart($model) {
        $this->dispatch('add-to-cart', $model);
    }

    public function mount() {
        $productArray = array();
        $this->slug = Request::route('slug');

        $model = str_replace('-',' ',$this->slug);

        $products = ProductRetail::where('model_name',"LIKE",'%'.$model.'%')->get();
        if ($products->count() > 1) {
            foreach ($products as $product)
                $productArray[] = ['id'=>$product->id,'model'=>$product->p_model,"parts"=>$product->total_parts,"size"=>$product->size,'weight'=>$product->weight,'dimensions'=>$product->dimensions,'price' => $product->p_retail];

                // Step 2: Sort to prioritize models ending in "S" over those ending in "L"
                usort($productArray, function ($a, $b) {
                    $aPriority = str_ends_with($a['model'], 'S') ? 1 : 2; // Priority: 1 for "S", 2 for "L"
                    $bPriority = str_ends_with($b['model'], 'S') ? 1 : 2;

                    return $aPriority <=> $bPriority; // Sort by priority
                });

                $filteredProducts = $products->reject(function ($product) {
                    return str_ends_with($product->p_model, 'L'); // Exclude SPL, CBL, HGL
                });
                $product = $filteredProducts->first();

        } else $product = $products->first();

        $this->currentProduct = $product;
        $this->model = $productArray[0]['model'] ?? $product->p_model;

        $this->model = strtolower($this->model);

        $this->size = $product->size;
        $banners = File::files(public_path("/images/product/$this->model/banners/"));

        foreach ($banners as $i => $file) {
            if ($file->getExtension() != 'jpg' && $file->getExtension() != 'jpeg' && $file->getExtension() != 'png')
                continue;
            $this->banners[] = $file->getFilename();
        }

    }

    #[Layout('components.layouts.app')]
    public function render()
    {

        return view('livewire.product-details')
            ->layoutData(['pageName' => 'Product Details','countCart' => Cart::count()])
            ->title("Product Details");
    }
}