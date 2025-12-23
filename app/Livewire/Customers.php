<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use App\Libs\SearchCriteriaTrait;
use Livewire\Attributes\Url;
// use Jantinnerezo\LivewireAlert\LivewireAlert;

class Customers extends Component
{
    use WithPagination, SearchCriteriaTrait;

    public $page = 1;
    public $customerSelections = [];

    #[Url(keep: true)]
    public $search = "";

    public $customerId = 0;

    protected $queryString = [
        'page',
        'search'
    ];

    public function deleteCustomer($id) {
        $customer = Customer::find($id);
        $customer->delete();
        // $this->alert('success', "Customer #$id has been deleted successfully.");
    }

    public function invokeCustomerId($id,$company) {
        $this->dispatch('set-customer',$id,$company);
    }

    public function createNewCustomer() {
        $this->dispatch('create-new');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $columns = ['company','firstname','lastname'];
        $searchTerm = $this->generateSearchQuery($this->search, $columns);

        $customers = Customer::when(strlen($searchTerm)>0, function($query) use ($searchTerm) {
            $query->whereRaw($searchTerm);
        })
            ->orderByRaw("COALESCE(company, CONCAT(firstname, ' ', lastname)) ASC")
            ->paginate(perPage: 16);

        return view('livewire.customers',['customers' => $customers,'pageName' => "Customers"])
            ->layoutData(['pageName' => 'Customers'])
            ->title("customers");
    }
}
