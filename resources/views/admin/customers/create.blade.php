@extends('layouts.admin-default')

@section ('content')

<?php $customer_groups = ['Customer','Dealer']; ?>

 <form method="POST" action="{{route('customers.store')}}">
    @csrf
     <div class="form-group row">
        <label for="customer-group-input" class="col-3 col-form-label">Customer Group</label>
        <div class="col-9">
            <select class="form-control" name="croup" id="customer-group-input">
                @foreach ($customer_groups as $value => $customer_group)
                    <option value="{{ $value }}" <?php echo !empty(old('customer_group')) && old('customer_group')==$customer_group ? 'selected' : '' ?>>{{ $customer_group }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="firstname-input" class="col-3 col-form-label">First Name</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('firstname')) ? old('firstname') : '' ?>" type="text" name="firstname" id="firstname-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="lastname-input" class="col-3 col-form-label">Last Name</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('lastname')) ? old('lastname') : '' ?>" type="text" name="lastname" id="lastname-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="company-input" class="col-3 col-form-label">Company</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?php echo !empty(old('company')) ? old('company') : '' ?>" type="text" name="company" id="company-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="address-input" class="col-3 col-form-label">Address 1</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?php echo !empty(old('address1')) ? old('address1') : '' ?>" type="text" name="address1" id="address-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="address2-input" class="col-3 col-form-label">Address 2</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?php echo !empty(old('address2')) ? old('address2') : '' ?>" type="text"  name="address2" id="address2-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="city-input" class="col-3 col-form-label">City</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('city')) ? old('city') : '' ?>" type="text" name="city" id="city-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="state-input" class="col-3 col-form-label">State</label>
        <div class="col-9">
            @inject('countries','App\Libs\Countries')
            <?php echo $countries->getAllStates(0,'') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="zip-input" class="col-3 col-form-label">Zip Code</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('zip')) ? old('zip') : '' ?>" type="text"  name="zip" id="zipcode-input">
        </div>
    </div>    
    <div class="form-group row">
        <label for="country-input" class="col-3 col-form-label">Country</label>
        <div class="col-9">
            <?php echo $countries->getAllCountries(0,'') ?>
        </div>
    </div>    
    <div class="form-group row">
        <label for="phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?php echo !empty(old('phone')) ? old('phone') : '' ?>" type="text"  name="phone" id="phone-input">
        </div>
    </div>    
    <div class="form-group row">
        <label for="email-input" class="col-3 col-form-label">Email</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('email')) ? old('email') : '' ?>" type="text" name="email" id="email-input">
        </div>
    </div>

    <div class="form-group row">
        <label for="website-input" class="col-3 col-form-label">Website</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('website')) ? old('website') : '' ?>" type="text" name="website" id="website-input">
        </div>
    </div>

    @role('superadmin')
    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>
    @endrole
    
    @include('admin.errors')
</form>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        @include ('admin.countrystate',['billExt'=>'', 'shipExt'=>''])
    })
</script>
@endsection;