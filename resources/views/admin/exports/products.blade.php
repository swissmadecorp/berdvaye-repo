<table>
    <tr>
        <td colspan="5" style="height: 40px"></td>
    </tr>
    <!-- Begin Header -->
    <tr>
        <th style="background-color: #e7e7e7; font-weight: bold">Name</th>
        <th style="background-color: #e7e7e7; font-weight: bold">Model</th>
        <th style="background-color: #e7e7e7; font-weight: bold">Qty</th>
        <th style="background-color: #e7e7e7; font-weight: bold">Serial</th>
        <th style="background-color: #e7e7e7; font-weight: bold">Retail</th>
    </tr>
    <!-- End Header -->

    <!-- Begin Body -->
    @foreach($products as $product)
        <tr>
            <td style="width: 166px">{{ $product->model_name }}</td>
            <td style="width: 75px">{{ $product->p_model }}</td>
            <td>{{ $product->p_qty }}</td>
            <td style="text-align: right">{{ $product->p_serial }}</td>
            <td style="text-align: right;width: 75px">{{ $product->p_retail }}</td>
        </tr>
    @endforeach
    <!-- End Body -->

    <!-- Begin Footer -->
    <tr>
        <td style="background-color: #e7e7e7;font-weight: bold">Total:</td>
        <td style="background-color: #e7e7e7;text-align: right"></td>
        <td style="background-color: #e7e7e7;text-align: right"></td>
        <td style="background-color: #e7e7e7;text-align: right"></td>
        <td style="background-color: #e7e7e7;text-align: right"></td>
    </tr>
    <!-- End Footer -->
</table>