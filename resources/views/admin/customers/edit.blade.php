@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')

    <?php 
        // echo __DIR__;
        $customer_groups = ['Customer','Dealer'];
    ?>
    
  <form method="POST" action="{{route('customers.update',[$customer->id])}}" accept-charset="UTF-8" >
    @csrf
    @method('PATCH')
    
    <div class="form-group row">
        <label for="customer-group-input" class="col-3 col-form-label">Customer Group</label>
        <div class="col-9">
            <select class="form-control" name="cgroup" id="customer-group-input">
                @foreach ($customer_groups as $value => $customer_group)
                    <option value="{{ $value }}" <?= $customer->cgroup == $value ? 'selected' : '' ?>>{{ $customer_group }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="firstname-input" class="col-3 col-form-label">First Name</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty($customer->firstname) ? $customer->firstname : '' ?>" type="text" name="firstname" id="firstname-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="lastname-input" class="col-3 col-form-label">Last Name</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty($customer->lastname) ? $customer->lastname : '' ?>" type="text" name="lastname" id="lastname-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="company-input" class="col-3 col-form-label">Company</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?= !empty($customer->company) ? $customer->company : '' ?>" type="text" name="company" id="company-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="address-input" class="col-3 col-form-label">Address 1</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?= !empty($customer->address1) ? $customer->address1 : '' ?>" type="text" name="address1" id="address-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="address2-input" class="col-3 col-form-label">Address 2</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?= !empty($customer->address2) ? $customer->address2 : '' ?>" type="text" name="address2" id="address2-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="city-input" class="col-3 col-form-label">City</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty($customer->city) ? $customer->city : '' ?>" type="text" name="city" id="city-input">
        </div>
    </div>    
    <div class="form-group row">
        <label for="state-input" class="col-3 col-form-label">State</label>
        <div class="col-9">
        @inject('countries','App\Libs\Countries')
            <?= $countries->getAllStates($customer->state,'') ?>
            <input type="hidden" value="{{$customer->state}}" id="h_State">
        </div>
    </div>
    <div class="form-group row">
        <label for="zip-input" class="col-3 col-form-label">Zip Code</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty($customer->zip) ? $customer->zip : '' ?>" type="text" name="zip" id="zipcode-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?= !empty($customer->phone) ? $customer->phone : '' ?>" type="text" name="phone" id="phone-input">
        </div>
    </div>      
    <div class="form-group row">
        <label for="country-input" class="col-3 col-form-label">Country</label>
        <div class="col-9">
            <?= $countries->getAllCountries($customer->country,'') ?>
        </div>
    </div>      
    <div class="form-group row">
        <label for="email-input" class="col-3 col-form-label">Email</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty($customer->email) ? $customer->email : '' ?>" type="text" name="email" id="email-input">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>
    
    <hr/>

    <h4>Previous Orders</h4>
    <hr/>
    <div class="table-responsive">
    <table id="orders" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Order Id</th>
                <th>Status</th>
                <th>Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        
        <?php $grandTotal = 0; ?>

        @foreach ($customer->orders as $order)
        <?php $grandTotal += $order->total ?>
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ OrderStatus()->get($order->status) }}</td>
            <td class="text-right">{{ $order->created_at->format('m/d/Y') }}</td>
            <td class="text-right">${{ number_format($order->total,2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th class="text-right" >Total</th>
            <th class="text-right" >${{ number_format($grandTotal,2) }}</th>
        </tr>
    </tfoot>
    </table>

</div>

    @include('admin.errors')
</form> 
@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {

        var table = $('#orders').DataTable({
            "deferRender": true,
            "columns": [
                { "width": "5%" },
                { "width": "15%" },
                { "width": "15%" },
                { "width": "20%" },
            ],
            "createdRow": function( row, data, dataIndex){
                if( data[1] ==  'Unpaid'){
                    $(row).addClass('unpaid');
                }
            }
        });

        stateFromCountry('#state-input','#country-input');

        function stateFromCountry(state,country) {
            $.get("https://berdvaye.com/getStateFromCountry",{id: $(country).val()})
            .done (function (data) {
                $(state).html(data);
                $("#state-input option[value='"+ {{$customer->state}} +"']").prop('selected','selected')
            })

        }

        $('#orders tbody').on('click', 'td', function () {
            var data = table.row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.column.index( 'fromVisible', visIdx );

            window.location.href = '/admin/orders/'+data[0];
        } );

        //$("#country-input option[value='"+ $('#selectedId').val()+"']").attr('selected','selected').change();
    })
</script>
@endsection