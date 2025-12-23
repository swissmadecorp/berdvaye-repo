@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/public/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')

{{  Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PATCH', 'id' => 'productform')) }} 
   
   
    <?php date_default_timezone_set('America/New_York'); ?>
    <div class="form-group row">
        <label for="createdat-input" class="col-3 col-form-label">Created At</label>
        <div class="col-9">
            <span class="form-control" >{{ $product->created_at->format('m-d-Y g:i:s A') }}</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="createdat-input" class="col-3 col-form-label">Image</label>
        <div class="col-9">
            <img class="form-control" style="width: 150px" src="/images/{{ $product->image() }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="reference-name-input" class="col-3 col-form-label">Reference Name</label>
        <div class="col-9">
            <span class="form-control"><?php echo !empty($product->p_reference) ? $product->p_reference : '' ?></span>
        </div>
    </div>
    <div class="form-group row">
        <label for="model-number-input" class="col-3 col-form-label">Model Number</label>
        <div class="col-9">
            <span class="form-control"><?php echo !empty($product->p_model) ? $product->p_model : '' ?></span>
        </div>
    </div>        
    <div class="form-group row">
        <label for="serial-name-input" class="col-3 col-form-label">Serial #</label>
        <div class="col-9">
            <span class="form-control"><?php echo !empty($product->p_serial) ? $product->p_serial : '' ?></span>
        </div>
    </div>
    @if ($product->retail->heighest_serial)
    <div class="form-group row">
        <label for="heighest_serial-name-input" class="col-3 col-form-label">Heighest Serial # *</label>
        <div class="col-9">
            <span class="form-control"><?php echo !empty($product->retail->heighest_serial) ? $product->retail->heighest_serial : '' ?></span>
        </div>
    </div>
    @endif    
    <div class="form-group row">
        <label for="price-input" class="col-3 col-form-label">Price</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span class="form-control">{{ $product->retailvalue() / 2 }}</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="retail-input" class="col-3 col-form-label">Retail Price</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span class="form-control">{{$product->retailvalue()}}</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="reference-input" class="col-3 col-form-label">Quantity</label>
        <div class="col-9">
            <span class="form-control"><?php echo !empty($product->p_qty) ? $product->p_qty : 0 ?></span>
        </div>
    </div>
    <div class="form-group row">
        <label for="reference-input" class="col-3 col-form-label">Status</label>
        <div class="col-9">
            @if ($product->p_status == 0)
            <span class="form-control" style="color: green">Available</span>
            @else
            <span class="form-control" style="color: red">Reserved - {{ Status()[$product->p_status] }}</span>
            @endif
        </div>
    </div>    
  
    <div class="form-group row">
        <label for="comments-input" class="col-3 col-form-label">Comments</label>
        <div class="col-9">
            <span id="comments-input" style="height: 150px; overflow-y:auto;overflow-x:hidden" class="form-control">{!! str_replace("\n","<br>",$product->comments) !!}</span>
        </div>    
    </div>

    @if (count($product->orders)>0 && $product->id != 1)
    <hr>
    <h4>Previous Orders</h4>
    <hr/>
    <div class="table-responsive">
    <table id="orders" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Invoice Id</th>
                <th>Customer</th>
                <th>Date Sold</th>
                <th>Method</th>
                <th>Serial #</th>
                <th>Sold Amount</th>
            </tr>
        </thead>
        <tbody>      
        @foreach ($product->orders as $invoice)
        <?php 
            $returned='';$product=$invoice->products->find($product->id);
            if (count($invoice->returns)) {
                if ($invoice->returns->find($product->id))
                    $returned = '-';
            }
        ?>
        <tr style="<?= $returned=='-' ? "background: #ffecec" : "" ?>" >
            <td><a href="/admin/orders/{{ $product->pivot->order_id }}">{{ $product->pivot->order_id }}</a></td>
            <td>{{ $invoice->customers->first()->company }}</td>
            <td>{{ $invoice->created_at->format('m/d/Y')}}</td>
            <td>{{ $invoice->method }}</td>
            <td>{{ $product->pivot->serial }}</td>
            <td class="text-right"><?= $returned ?>${{ number_format($product->pivot->price,2) }}</td>   
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>
    @endif

    <a href="{{ URL::to('admin/products/'.$product->id.'/print') }}" class="btn btn-primary">Print Barcode</a>

{{  Form::close() }}  

<div id="search-customer"></div>
@endsection