@extends('layouts.admin-new-default')

@role('superadmin')

@section ('header')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endsection

@section ('footer')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
@endsection

@section ('content')

    @if (!$backorders->isEmpty())
    <div class="table-responsive"><h1>Backorder Orders</h1>
        <table class="w-full text-sm text-left rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-3 py-3">Id</th>
                    <th scope="col" class="px-3 py-3">Invoice</th>
                    <th scope="col" class="px-3 py-3">Company</th>
                    <th scope="col" class="px-3 py-3">PO</th>
                    <th scope="col" class="px-3 py-3">Amount</th>
                </tr>
            </thead>
            <tbody>
            
            @foreach ($backorders as $order)

                <tr class="odd:bg-white hover:bg-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-3 py-2"><a href="admin/orders/{{ $order->id }}">{{ $order->id }}</a></td>
                    <td class="px-3 py-2">@if ($order->method=='On Memo')
                            On Memo
                        @else
                            Invoiced
                        @endif

                        @if ($order->emailed)
                            <i class="far fa-envelope" title="Invoice was emailed"></i>
                        @endif
                    </td>
                    <td class="px-3 py-2">{{ $order->b_company }}</td>
                    <td class="px-3 py-2">{{ $order->po }}</td>
                    <td class="px-3 py-2 text-right">${{ number_format($order->total,2) }}</td>
                </tr>
            @endforeach


            </tbody>
        </table>

    </div>
    @endif

    @if ($repairs)
    <div class="table-responsive"><h1>Products sent for Repair</h1>
        <table class="w-full text-sm text-left rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-3 py-3">Id</th>
                    <th scope="col" class="px-3 py-3">Company</th>
                    <th scope="col" class="px-3 py-3">Product</th>
                    <th scope="col" class="px-3 py-3">Serial</th>
                </tr>
            </thead>
            <tbody>
            
            @foreach ($repairs as $order)
                <tr class="odd:bg-white hover:bg-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-3 py-2"><a href="admin/orders/{{ $order['id'] }}">{{ $order['id'] }}</a></td>
                    <td class="px-3 py-2">{{ $order['company'] }}</td>
                    <td class="px-3 py-2">{{ $order['product']}}</td>
                    <td class="px-3 py-2">{{ $order['serial']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    @endif

    <div class="table-responsive"><div style="position: relative"><h1>Past Due Invoices</h1><div style="position: absolute;top: 16px;right: 0;">
        <button type="button" id="print" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Print
        </button></div></div>
    <table class="w-full text-sm text-left rtl:text-right dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-3 py-3">Id</th>
                <th scope="col" class="px-3 py-3">Invoice</th>
                <th scope="col" class="px-3 py-3">Company</th>
                <th scope="col" class="px-3 py-3">Status</th>
                <th style="width: 80px" class="px-3 py-3">Past Due</th>
                <th scope="col" class="px-3 py-3">Amount</th>
            </tr>
        </thead>
    <tbody>
    
    <?php $total=0; ?>
    @foreach ($invoices as $order)
        <?php
            if ($order->status==0)
                $status='Unpaid';
            elseif ($order->status==1)
                $status='Paid*';
            elseif ($order->status==2) 
                $status = "Return";
            else $status='Transferred';
        ?>
        <tr class="odd:bg-white hover:bg-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
            <td class="px-3 py-2"><a href="admin/orders/{{ $order->id }}">{{ $order->id }}</a></td>
            <td class="px-3 py-2">@if ($order->method=='On Memo')
                    On Memo
                @elseif ($order->method=='Invoice')
                    Invoiced
                @else
                    <span style="color: red">At Repair</span>
                @endif

                @if ($order->emailed)
                    <i class="far fa-envelope" title="Invoice was emailed"></i>
                @endif
            </td>
            <td class="px-3 py-2">{{ $order->b_company }}</td>
            <td class="px-3 py-2">{{ $status }}</td>
            <td class="px-3 py-2">
                <?php 
                    $to = date('Y-m-d',time());
                    
                    $dStart = new \DateTime($to);
                    $dEnd  = new \DateTime($order->created_at);
                    $dDiff = $dStart->diff($dEnd);
                    
                ?>

                @if ($dDiff->days>365)
                    {{$dDiff->y }} years
                @elseif ($dDiff->days > 31)
                    {{ $dDiff->m }} months
                @else
                    {{ $dDiff->days }} days
                @endif
            </td>
            <?php $subtotal = $order->total - $order->payments->sum('amount') ?>
            
            @foreach($order->orderReturns as $returns)
                <?php $subtotal -= $returns->pivot->amount*$returns->pivot->qty; ?>
            @endforeach

            <?php $total += $subtotal ?>
            <td class="px-3 py-2 text-right">${{ number_format($subtotal,2) }}</td>
        </tr>
    @endforeach
        <tfoot>
            <tr style="background: #ccc; font-weight: bold">
                <td class="px-3 py-2" colspan="5">Total Owned</td>
                <td class="px-3 py-2 text-right">${{number_format($total,2)}}</td>
            </tr>
        </tfoot>
    </tbody>
</table>

</div>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        $("#print").click(function(e) {
            e.preventDefault();
            window.location.href="{{route('print.all.owed')}}";
            
        })
    })
</script>
    @endsection
@endrole