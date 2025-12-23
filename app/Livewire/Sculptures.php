<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductRetail;
use Livewire\Attributes\Layout;

class Sculptures extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        $products = ProductRetail::select(
                'model_name', 'p_model', 'description',
                \DB::raw('MIN(p_retail) as min_retail'),
                \DB::raw('MAX(p_retail) as max_retail'),
                \DB::raw('(SELECT image_location FROM product_retails pr2 WHERE pr2.model_name = product_retails.model_name LIMIT 1) as image_location')
            )
        ->where('is_active', 1)
        ->groupBy('model_name')
        ->orderBy('id','asc')
        ->get();


        return view('livewire.sculptures', ['products' => $products, 'activePage' => 'sculptures'])
            ->layoutData(['pageName' => 'Sculptures'])
            ->title("Sculptures");
    }

    public function goToProductDetails() {
        //  return redirect()->route('product-details', ['model' => $this->model]);
    }
}
