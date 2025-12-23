
@if ($products)
<?php $discount = 0?>
<table class="table hover carttable">
    <thead class="td-bk">
    <tr style="border:1px solid #dee2e6">
        <th class="w-[100px]">Image</th>
        <th>Name</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
        <?php $totals=0;$freight=0 ?>

        @if (!empty($order))
            <?php
                $tax = $order->taxable;
                $total = $order->subtotal;
            ?>
        @endif

        @foreach ($products as $product)

        <tr class="td-border">
            <td class="relative flex justify-center items-center w-[100px]" v-if="details.images">
                <img src="{{$product['image']}}" style="width: 80px">
                @if ($step != 2)
                    <button class="hover:text-red-600 cursor-pointer remove absolute border-0 bg-transparent w-35px inset-x-0 top-0 p-1" data-id="{{ $product['id'] }}">
                        <img src="/assets/remove_red.png" class="w-5" alt="Remove Item">
                    </button>
                @endif
            </td>
            <td class="align-middle"><div>{{ $product['size'] }} {{ $product['model_name'] }}</div>
                @if ($product['qty'] < 1)
                    <p style="color: red">Item is on back order and will be shipped in 4 - 6 weeks.</p>
                @endif
            </td>
            <td class="align-middle text-right" style="width: 150px">${{ number_format($product['price'],2) }}</td>
        </tr>
        <?php $totals +=$product['price'] ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-right">Sub Total:</th>
            <td style="border:1px solid #dee2e6" class="td-bk text-right w-25">${{ number_format($totals,2) }}</td>
        </tr>
        <tr class="tax-amount">
            <th colspan="2" class="text-right">Tax: </th>
            @if (empty($tax))
            <td style="border:1px solid #dee2e6" class="td-bk text-right">$0.00</td>
            @else
            <td style="border:1px solid #dee2e6" class="td-bk text-right">{{$tax}}%</td>
            @endif
        </tr>
        <tr>
            <th colspan="2" class="text-right">Shipping: </th>
            <td style="border:1px solid #dee2e6" class="td-bk text-right">Free FedEx Ground</td>
        </tr>
        <tr>
            <th colspan="2" class="text-right">Grand Total:</th>
            @if (empty($tax))
                <td style="border:1px solid #dee2e6" class="newtotal td-bk text-right">${{ number_format($totals,2) }}</td>
            @else
                <?php $total = number_format($totals + ($totals * ($tax/100)),2); ?>
                <td style="border:1px solid #dee2e6" class="td-bk text-right">${{$total}}</td>
            @endif
        </tr>
    </tfoot>
</table>
@endif