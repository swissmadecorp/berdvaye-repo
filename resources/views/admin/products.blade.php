@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css"/> 
<link rel="stylesheet" href="//cdn.materialdesignicons.com/2.3.54/css/materialdesignicons.min.css">
@endsection

@section ('content')
<div class="alert-info clearfix" style="padding: 3px">
    <div class="dropdown float-right">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Actions
        </button>
        <div class="dropdown-menu" style="left: -87px" aria-labelledby="dropdownMenu2">
            @role('superadmin')
            <button class="dropdown-item" id="duplicate" type="button">Duplicate</button>
            <hr>
            <button class="dropdown-item" id="delete" type="button">Delete</button>
            <hr>
            <button class="dropdown-item" id="export" type="button">Export to Excel</button>
            <hr>
            @endrole
            <button class="dropdown-item" id="print_all" type="button">Print All</button>
            <button class="dropdown-item" id="print_in_stock" type="button">Print In Stock</button>
            <button class="dropdown-item" id="print_not_in_stock" type="button">Print Not In Stock</button>
            <!-- <button class="dropdown-item" id="print_sold" type="button">Print Sold</button> -->
            <button class="dropdown-item" id="print_nonmemo" type="button">Print Non-Memo</button>
            <hr>
            <button class="dropdown-item" id="print_none_available" type="button">Print None Available</button>
        </div>
    </div>
    <div class="float-right">
            <input type="text" style="width: 90%;padding: 5px;" placeholder="Year Filter" id="datepicker">
    </div>
</div>

<br>
<div class="pretty p-icon p-jelly p-round">
    <input type="radio" name="icon_solid" id="on-hand" checked/>
    <div class="state p-primary">
        <i class="icon mdi mdi-check"></i>
        <label>Display on-hand</label>
    </div>
</div>

<div class="pretty p-icon p-jelly p-round">
    <input type="radio" name="icon_solid" id="display-all" />
    <div class="state p-success">
        <i class="icon mdi mdi-check"></i>
        <label>Display not on-hand</label>
    </div>
</div>

<hr/>
<div class="table-responsive">
<table id="products" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Title</th>
            <th>Serial#</th>
            <th>Price</th>
            <th>Retail</th>
            <th>Qty</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right" style="font-weight:bold">Total</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-center"></td>
            <td></td>
        </tr>
    </tfoot>
</table>
</div>

@role('superadmin')
<form action="products/create">
    <button type="submit" class="btn btn-primary">Create New</button>
</form>
@endrole

@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var table;
    $(document).ready( function() {
        function initTable(action,display) {
            if (!display)
                display = 'Display on-hand';

            table = $('#products').DataTable({
                "deferRender": true,
                ajax: {
                    url: "{{ URL::route('getAll') }}",
                    data: {display:display},
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                scrollX:        true,
                scrollCollapse: true,
                autoWidth:      true,  
                paging:         true,
                "initComplete": function(settings, json) {
                    var api = this.api();
                    $( api.column( 4 ).footer() ).html(json.totalprice)
                    $( api.column( 5 ).footer() ).html(json.totalretail)
                    $( api.column( 6 ).footer() ).html(json.qty);
                    $('div.dataTables_filter input').focus();
                },

                stateSave: true,
                columnDefs: [ {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0
                },
                {
                    className: 'availability-container',
                    targets:   2
                }],
                "aaSorting": [ 1, 'desc'],
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                }            
            });

        }

        $('#on-hand').prop('checked',true);
        $('#display-all,#on-hand').click( function() {
            //if ($(this).is(':checked')) return
            table.destroy();
            initTable('active',$(this).next().find('label').text());
        })

        @if ($order_reminder = session()->get('order_reminder'))
            $.confirm({
                title: 'Backorder Item',
                content: 'A backordered item is waiting to be shipped for order # {{$order_reminder["order_id"]}}. Would you like me to create that order for you for the following item {{$order_reminder["item"]}}?',
                buttons: {
                    formSubmit: {
                        text: 'Create order',
                        btnClass: 'btn-blue',
                        action: function () {
                            var order = new Array({
                                order_id: {{$order_reminder["order_id"]}},
                                product_id: {{$order_reminder["product_id"]}}, 
                                price: {{$order_reminder["price"]}},
                                model: '{{$order_reminder["model"]}}',
                                product_name: '{{$order_reminder["item"]}}',
                                serial: {{$order_reminder["serial"]}}
                            });

                            var request = $.ajax({
                                url: "{{ route('create.backorder.product') }}",
                                method: 'POST',
                                // contentType: "application/json",
                                // dataType: "json",
                                data: {order: order[0]},
                                success: function (result) {
                                    $.alert(result)
                                }
                            })
                            request.fail( function (jqXHR, textStatus) {
                                $.alert (textStatus)
                            })      
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                }
            });

            {{ session()->forget('order_reminder') }}
        @endif

        $('#products tbody').on('click', 'td', function () {
            var data = table.row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.column.index( 'fromVisible', visIdx );

            if (table.column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                window.location.href = 'products/'+data[1]+'/edit';
            }
        } );
        
        initTable('active');
        $('.dropdown-menu button').click( function(e) {
            e.preventDefault();
            
            if (e.currentTarget.id == 'print_all') {
                window.location.href="products/1/printinventory/all";
                return
            } else if (e.currentTarget.id == 'print_sold') {
                window.location.href="products/1/printinventory/sold/"+$('#datepicker').val();
                return
            } else if (e.currentTarget.id == 'export') {
                window.location.href="products/1/export";
                return
            } else if (e.currentTarget.id == 'print_in_stock') {
                window.location.href="products/1/printinventory/stock";
                return
            } else if (e.currentTarget.id == 'print_not_in_stock') {
                window.location.href="products/1/printinventory/nostock";
                return
            } else if (e.currentTarget.id == 'print_nonmemo') {
                window.location.href="products/1/printinventory/nomemo";
                //Large Picture Frame Time Framed 9
                return
            } else if (e.currentTarget.id == 'print_none_available') {
                if (model = prompt('Please input model name and press enter','SKS')) {
                    window.location.href="products/"+model+"/printmissingproducts";
                }

                return
            }

            id = table.rows( { selected: true } ).data();
            
            if (id[0] == undefined) {
                alert('Please select a product and try again.');
                return;
            }
            if (e.currentTarget.id == 'delete') {
                if (confirm('Are you sure you want to delete selected product?')) {
                    window.location.href="products/"+id[0][1]+'/destroy';
                }
            } else if (e.currentTarget.id == 'duplicate') {
                window.location.href="products/"+id[0][1]+'/duplicate';
            } 

        })
    })    
</script>
@endsection