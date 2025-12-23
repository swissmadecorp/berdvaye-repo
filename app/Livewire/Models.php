<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Jantinnerezo\LivewireAlert\Enums\Position;
use Livewire\Attributes\On;
use App\Models\ProductRetail;
use Livewire\WithPagination;
use App\Libs\SearchCriteriaTrait;

use Livewire\Component;

class Models extends Component
{
    use WithPagination,  SearchCriteriaTrait;

    #[Url(keep: true)]
    public $search = "";
    public $sortDirection = "DESC";
    public $sortBy = "created_at";

    protected $queryString = [
        'search',
    ];

    #[On('invoke-model')]
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

    public function deleteModel($id){
        $model = ProductRetail::find($id);
        if ($model) {
            $model->delete();
            LivewireAlert::title('Model deleted successfully.')->success()->position(Position::TopEnd)->toast()->show();
        } else {
            LivewireAlert::title('Model not found.')->error()->position(Position::TopEnd)->toast()->show();
        }
    }

    public function updatedStatus(){
        $this->updatingSearch();
    }

    public function render(){
        $columns = ['p_model','model_name'];
        $searchTerm = $this->generateSearchQuery($this->search, $columns);

        $products = ProductRetail::when(strlen($searchTerm)>0, function($query) use ($searchTerm) {
            $query->whereRaw($searchTerm);
        })
        ->orderBy($this->sortBy, $this->sortDirection);

        $products = $products->paginate(perPage: 10);

        return view('livewire.models',["products"=>$products, 'pageName' => 'Models'])
            ->layoutData(['pageName' => 'Models'])
            ->title("models");
    }
}
