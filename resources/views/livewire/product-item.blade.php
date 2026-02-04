<div>
    <!-- Berdvaye Product Item -->
@push('main_header')
<!-- <link href="/css/dropzone.css" rel="stylesheet"> -->
<link href="/js/editable-select/jquery-editable-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery-bundle.min.css" integrity="sha512-nUqPe0+ak577sKSMThGcKJauRI7ENhKC2FQAOOmdyCYSrUh0GnwLsZNYqwilpMmplN+3nO3zso8CWUgu33BDag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push ('footer')
<script src="/js/jquery.autocomplete.min.js"></script>
<script src="/js/jquery.mask.js" type="text/javascript"></script>
<script src="/js/editable-select/jquery-editable-select.js"></script>
@endpush

    <!-- Berdvaye product-item -->
    <div wire:ignore.self id="slideover-product-container" class="fixed inset-0 w-full h-full invisible z-[51]" >
        <div wire:ignore.self id="slideover-product-bg" class="absolute duration-500 ease-out transition-all inset-0 w-full h-full bg-gray-900 opacity-0"></div>
        <div tabindex="0" wire:ignore.self id="slideover-product" class="border absolute duration-500 ease-out transition-all h-full bg-white right-0 top-0 translate-x-full md:w-[665px] w-[390px] dark:bg-gray-800 overflow-x-hidden">
            <div class="bg-gray-200 dark:bg-gray-600 dark:text-gray-300 p-3 text-2xl text-gray-500">
                @if ($productId)
                    Edit Item
                @else
                    New Item
                @endif
            </div>
            <div @click="isSliderVisible = false" id="slideover-product-child" class="p-2 flex items-center shadow-sm rounded-full justify-center hover:bg-gray-300 cursor-pointer absolute top-0 right-0 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap dark:bg-gray-900 text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                    <li class="me-2" role="presentation">
                        <button wire:ignore.self class="inline-block p-4 border-b-2 rounded-t-lg" id="product-tab" data-tabs-target="#product" type="button" role="tab" aria-selected="true" aria-controls="profile">Product</button>
                    </li>

                    <li x-data="{ ordercount: @entangle('totalorders')}" x-cloak class="me-2" :class="{'hidden': ordercount === 0}"
                        role="presentation">
                        <button wire:ignore.self class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="
                        -tab" data-tabs-target="#invoices" type="button" role="tab" aria-selected="false" aria-controls="invoices" >Invoices</button>
                    </li>
                </ul>
            </div>

            <div id="default-styled-tab-content" class="dark:bg-gray-800">
                <div class="p-4 rounded-lg dark:bg-gray-800" id="product" role="tabpanel" aria-labelledby="product-tab">
                    <form>
                        <div class="flex justify-between text-sm text-gray-600">
                            <div class="dark:text-gray-200">
                                @if ($productId)
                                <span class="font-bold">Product #:</span> {{$productId}}
                                @endif
                            </div>
                            <div class="dark:text-gray-200">
                                <span class="font-bold">Date:</span> <span>@if ($created_date) {{date("m-d-Y",strtotime($created_date))}} @endif</span>
                            </div>
                        </div>

                        @if (isset($item['image']))
                        <div class="border mt-4 mb-4 shadow-sm flex justify-center p-2 bg-gray-100 dark:bg-gray-700">
                            <img class="w-50" src="{{$item['image']}}" alt="">
                        </div>
                        @endif

                        <x-input-standard model="item.p_model" label="p_model" position="uppercase" text="Model Number" validation />
                        <div class="grid gap-2 mb-2 md:grid-cols-2">
                            <x-input-standard model="item.model_name" label="model_name" text="Model Name" validation />
                            <x-input-standard model="item.p_serial" label="p_serial" text="Serial #" validation />
                            <x-input-standard model="item.p_qty" label="p_qty" text="On Hand" validation />
                            <div class=" pb-2.5">
                                <div :class="{'flex items-center': false }" class="">
                                    <label class="block font-medium text-sm text-gray-900 dark:text-white ">Retail Price</label>
                                    <span class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{isset($item['retail']) ? $item['retail'] : '0.00'}}</span>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                    <select id="status" wire:model.live="status" wire:change="status" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="-1"></option>
                                        @foreach (Status() as $key => $stats)
                                            <option value="{{ $key }}">{{ $stats }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("status")
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>

                        </div>

                        @role('superadmin|administrator')
                        <div class="flex justify-end">
                            <button wire:click="saveProduct()" type="button" class="text-white mt-4 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                @if ($productId)
                                    Update Product
                                @else
                                    Create Product
                                @endif
                            </button>
                        </div>
                        @endrole
                    </form>
                </div>

                <div class="hidden p-4 rounded-lg dark:bg-gray-800" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        @if ($totalorders)
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Invoice Id</th>
                                    <th scope="col" class="px-6 py-3">Customer</th>
                                    <th scope="col" class="px-6 py-3">Invoice</th>
                                    <th scope="col" class="px-6 py-3">Date Sold</th>
                                    <th scope="col" class="px-6 py-3">Serial #</th>
                                    <th scope="col" class="px-6 py-3">Sold Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $invoice)
                                <?php
                                    $product=$invoice->products->find($product->id);

                                    $colColor = '';
                                    foreach ($invoice->returns as $return) {
                                        if ($return->pivot->product_id==$product->id) {
                                            $colColor = "#f5bdada6";
                                        }
                                    }
                                ?>
                                <tr x-data="{colColor: @js($colColor)}"
                                    :class="colColor!=='' ? 'bg-red-100 odd:dark:bg-red-900 border-b dark:border-red-700' : 'hover:bg-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700'"

                                    class="border-b dark:border-gray-700">

                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                        <a @click="$dispatch('load-invoice', { id: {{$product->pivot->order_id}} })" data-id="{{$product->pivot->order_id}}" class="editinvoice cursor-pointer dark:hover:text-white text-sky-600">{{$product->pivot->order_id}}</a>
                                    </th>
                                    <td class="px-6 py-4">{{ $invoice->customers->first()->company }}</td>
                                    <td class="px-6 py-4">{{ $invoice->method }}</td>
                                    <td class="px-6 py-4">{{ $invoice->created_at->format('m/d/Y')}}</td>
                                    <td class="px-6 py-4">{{ $product->pivot->serial }}</td>
                                    <td class="px-6 py-4 text-right"><?= $colColor ? "-" : '' ?>${{ number_format($product->pivot->price,2) }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @else
                            <div class="font-medium p-5">No Invoice Found</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@script
    <script>
        $(function() {

            // var isSliderVisible = false;

            function Slider() {
                $('body').toggleClass('overflow-hidden')
                $('#slideover-product-container').toggleClass('invisible')
                $('#slideover-product-bg').toggleClass('opacity-0')
                $('#slideover-product-bg').toggleClass('opacity-20')
                $('#slideover-product').toggleClass('translate-x-full')
                if (!$('#slideover-product-container').hasClass('invisible')) {
                    setTimeout(() => {
                        $('#title').focus();
                    }, "400");

                } else {
                    $('#table-search').focus();
                }
            }

            $wire.on('newProductData', msg => {
                Livewire.dispatch('refresh');
            })

            $wire.on('display-message', msg => {
                // Clears editable select. There is no native function so here's a workaround

                $('.es-list li').show();
                $('.es-list li').removeClass('selected')
                $('.es-list li').addClass('es-visible')
                $('.es-list li:first').addClass('es-visible selected')
                // debugger

                if (typeof msg[0].hide === typeof undefined)
                    hide = 1
                else hide = msg[0].hide

                if (hide==1)
                    Slider()

            });

            $(document).on('click', '.editproduct', function() {
                if ($('#slideover-product-container').hasClass('invisible')==true) {
                    $wire.$call("editItem", $(this).attr('data-id'))

                    Slider()

                    if (!$('#slideover-invoice-container').hasClass('invisible')) {
                        $('#slideover-product-container').css('z-index',51)
                            $('#invoices-tab').hide();
                    } else {
                        $('#invoices-tab').show();
                        const productTabButton = document.getElementById('product-tab');
                        if (productTabButton) {
                            productTabButton.click();
                        }
                    }
                }
            })

            window.closeFields = function() {
                // This insures that the light gallery is destroyed when the slider is closed
                // because it creates multiple instances.
                if ($('[id^=lg-container-]').length) {
                    $('[id^=lg-container-]').remove();
                }
                Slider()
                $wire.$call('clearFields')
                const productTabButton = document.getElementById('product-tab');
                if (productTabButton) {
                    productTabButton.click();
                }
            }

            $('#slideover-product-child').click(function() {
                closeFields();
            })

            $('#newproduct').click(function() {
                Slider()
            })

            $('#p_model').devbridgeAutocomplete({
                serviceUrl: "{{route('get.retailproducts')}}",
                showNoSuggestionNotice : true,
                minChars: 1,
                width: 240,
                zIndex: 900,
                onSelect: function (suggestion) {
                    $wire.$call('setNewModelNumber', suggestion)
                }
            });

            $('#category').editableSelect({ effects: 'fade' })
                .on('select.editable-select', function (e, li) {
                    $wire.$set('category_selected_id',li.val());
                    $wire.$set('category_selected_text',li.text());
            });

        })
    </script>
@endscript

</div>