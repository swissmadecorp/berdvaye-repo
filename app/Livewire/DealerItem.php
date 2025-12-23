<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use App\Libs\SearchCriteriaTrait;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Dealer;
use Livewire\Component;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

class DealerItem extends Component
{
    use WithPagination, SearchCriteriaTrait;

    public $dealerId;
    public $dealer = [];
    
    public $dealerOrder = null;
    public $page = 1;
    public $search = "";
    public $searchSupplier = "";

    public function clearFields() {   
        $this->resetValidation();
        $this->reset();
        // Clear all items in the collection

    }

    protected function rules() {
        return [
            'dealer.customer' => ['required'],
        ];
    }

    protected $messages = [
        'dealer.customer.required' => 'Dealer name field is required.',
    ];

    public function lookupByName() {
        
        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?' . http_build_query([
            'types' => 'establishment',
            'input' => urlencode($this->dealer['customer']),
            'key' => 'AIzaSyDKnfoo5rNRrCWlG6MfRf5HB80DZN6niRY'
        ]);

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        curl_close($ch);

        // Decode the JSON response
        $predictions = json_decode($response, true);
        $addresses = [];
        $i = 0;

        foreach ($predictions['predictions'] as $prediction) {
            $addresses[] = $prediction['description'];
        }

        $this->dealer['companies'] = $addresses;
    }

    public function lookUpCoordinates() {
        $httpClient = new \GuzzleHttp\Client();
        $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, null, 'AIzaSyDKnfoo5rNRrCWlG6MfRf5HB80DZN6niRY');
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create($this->dealer['address']));
        
        $address = $result->first();

        $this->dealer['lat'] = $address->getCoordinates()->getLatitude();
        $this->dealer['lng'] = $address->getCoordinates()->getLongitude();
    }

    public function saveDealer() {
        
        $validatedData = $this->validate(
            $this->rules(),
            $this->messages
        );
    

        $data = $this->dealer; // Get the full data
        // unset($data['orders']); // Remove the 'orders' array
        if ($this->dealerId)
            $this->dealerOrder->update($data);
        else $this->dealerOrder = Dealer::create($data);

        $this->dispatch('display-message',['msg'=>'Dealer Saved.','id'=>$this->dealerOrder->id]);
    }

    #[On('set-dealer')]
    public function setDealerId($id) {
        $this->dealerId = $id;
        $this->dealerOrder = Dealer::find($this->dealerId);
        $this->dealer = $this->dealerOrder->toArray();
    }

    
    public function render() {
        
        $dealer = null;
        $id = $this->dealerId;

        if ($this->dealerId) {
            $columnsOrder = ['id','b_company','b_lastname','b_firstname', 'b_company'];
            $searchTermOrder = $this->generateSearchQuery($this->search, $columnsOrder);

            $dealer = Dealer::when(strlen($searchTermOrder) > 0, function ($query) use ($searchTermOrder) {
                $query->whereRaw($searchTermOrder);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);// Adjust pagination size as needed


        }

        return view('livewire.dealer-item', ['dealer' => $dealer]);
    }
}
