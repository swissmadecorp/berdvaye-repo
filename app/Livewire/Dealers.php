<?php

namespace App\Livewire;

use App\Models\Dealer;
use Livewire\Component;
use Livewire\WithPagination;
use App\Libs\SearchCriteriaTrait;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Jantinnerezo\LivewireAlert\Enums\Position;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Dealers extends Component
{
    use WithPagination, SearchCriteriaTrait;

    public $page = 1;
    public $dealerSelections = [];

    #[Url(keep: true)]
    public $search = "";

    public $dealerId = 0;

    protected $queryString = [
        'page',
        'search'
    ];

    public function deleteDealer($id) {
        $dealer = Dealer::find($id);
        $dealer->delete();
        // $this->alert('success', "Dealer #$id has been deleted successfully.");
    }

    public function invokeDealerId($id) {
        $this->dispatch('set-dealer',$id);
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

    public function createNewDealer() {
        $this->dispatch('create-new');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $geo = "https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA";


        // $result = $geocoder->reverseQuery(ReverseQuery::fromCoordinates(...));

        $columns = ['address','customer'];
        $searchTerm = $this->generateSearchQuery($this->search, $columns);

        $dealers = Dealer::when(strlen($searchTerm)>0, function($query) use ($searchTerm) {
            $query->whereRaw($searchTerm);
        })
            ->orderBy("customer", "ASC")
            ->paginate(perPage: 16);

        return view('livewire.dealers',['dealers' => $dealers,'pageName' => "Dealers"])
            ->layoutData(['pageName' => 'Dealers'])
            ->title("dealers");
    }
}
