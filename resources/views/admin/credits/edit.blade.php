@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 

<link href="{{ asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection

@section ('content')
@inject('countries','App\Libs\Countries')
<div class="customer_address pb-2">
    <?php 
        $address2 = '';
        
        $customer=$credit->customers->first();

        $state = $countries->getStateCodeFromCountry($customer->state);
        $country = $countries->getCountry($customer->country);

        echo $customer->company.'<br>';
        echo !empty($customer->address1) ? $customer->address1 .'<br>' : '';
        echo !empty($customer->address2) ? $customer->address2 .'<br>' : '';
        echo !empty($customer->city) ? $customer->city .', '. $state . ' ' . $customer->zip.'<br>': '';
        
        echo !empty($customer->phone) ? $customer->phone . '<br>' : '';
    ?>

</div>
<hr>
    <form method="POST" action="{{route('credits.update',[$credit->id])}}" >
    @csrf
    @method('PATCH')

    <div class="form-group row">
        <label for="currentamount-input" class="col-3 col-form-label">Available credit</label>
        <div class="col-9">
        <span id="currentamount" class='form-control'>{{$credit->amount}}</span>
        </div>    
    </div>
    <div class="form-group row">
        <label for="newamount-input" class="col-3 col-form-label">New amount</label>
        <div class="col-9">
        <input type="text" id="newamount" class='form-control' autofocus name="amount" required/>
        </div>    
    </div>
    <div class="form-group row">
        <label for="reference-input" class="col-3 col-form-label">Reference</label>
        <div class="col-9">
        <span id="reference" class='form-control'>{{$credit->ref}}</span>
        </div>    
    </div>
    @role('superadmin')
    <input class="btn btn-primary" type="submit" value="Save">
    @endrole
    
    @include('admin.errors')
</form>

@endsection