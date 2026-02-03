<?php

if (! function_exists('Materials')) {
    function Materials() {
        return collect(['Select material for this product','Glass','Leather','18k Yellow Gold','14k Yellow Gold',
            '18k White Gold','14k White Gold','Silver','Stainless Steel','Rubber','Canvas']);
    }
}

if (! function_exists('Conditions')) {
    function Conditions() {
        return collect(['Select condition for this product','New','Unworn','Store Display']);
    }
}

if (! function_exists('Status')) {
    function Status() {
        return collect(['Available', 'On Memo', "On Hold",'Sold','Unavailable/Lost','At Repair Center']);
    }
}

if (! function_exists('addressFromZip')) {
    // Load USPS module to get the city and state based on the zip code
    function addressFromZip($zipcode) {
        $request_doc_template =
        '<?xml version="1.0"?>
        <CityStateLookupRequest USERID="889SWISS3253">
            <ZipCode ID="0">
                <Zip5>'.$zipcode.'</Zip5>
            </ZipCode>
        </CityStateLookupRequest>';

        // prepare xml doc for query string
        $doc_string = preg_replace('/[\t\n]/', '', $request_doc_template);
        $doc_string = urlencode($doc_string);

        $url = "https://production.shippingapis.com/ShippingAPI.dll?API=CityStateLookup&XML=" . $doc_string;

        // perform the get
        $response = file_get_contents($url);

        $xml=simplexml_load_string($response) or die("Error: Cannot create object");

        //echo "Address1: " . $xml->ZipCode->Zip5 . "<br>";
        //echo "Address2: " . $xml->ZipCode->City . "<br>";
        //echo "State: " . $xml->ZipCode->State . "<br>";
        $countries = new \App\Libs\Countries;
        $country_b = $countries->getStateByCode($xml->ZipCode->State);

        if ($xml->ZipCode->City) {
            return array('city'=>(STRING) ucwords(strtolower($xml->ZipCode->City)),'state'=>$country_b);
        }else{
            return array('city'=>'','state'=>'');
        }

    }
}

if (! function_exists('Margins') ) {
    function Margins() {
        return collect(['cbs'=>10,'cbl'=>12,'hgs'=>10,'hgl'=>12,'sps'=>10,'spl'=>12,'sks'=>10,'skl'=>12,'cbl-ha'=>12,'cbl-gr'=>12,'sks-r'=>10,'cbl-ga'=>12,'frs'=>10,'frl'=>12]);
    }
}

if (! function_exists('Payments')) {
    function Payments() {
        return collect(['Invoice'=>'Invoice','On Memo'=>'On Memo','Repair' => 'Repair']);
    }
}

if (! function_exists('orderStatus')) {
    function orderStatus() {

        return collect(['Unpaid','Paid','Transferred','Returned','Repair']);
    }
}

if (! function_exists('localize_us_number')) {
    function localize_us_number($phone) {
        $numbers_only = preg_replace("/[^\d]/", "", $phone);
        return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
    }
}

if (! function_exists('PaymentsOptions')) {
    function PaymentsOptions() {
        return collect(['Due upon receipt'=>'Due upon receipt','Net-10'=>'Net 10','Net-15'=>'Net 15','Net-30'=>'Net 30','Net-60'=>'Net 60','Net-90'=>'Net 90','Net-120'=>'Net 120','Net-180'=>'Net 180','None'=>'None','Amex'=>'American Express', 'Visa'=>'Visa','Discover'=>'Discover','MC'=>'Master Card']);
    }
}