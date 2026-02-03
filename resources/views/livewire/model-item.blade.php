<div>
    <!-- Berdvaye Product Item -->
@section('main_header')
<!-- <link href="/css/dropzone.css" rel="stylesheet"> -->
<link href="/js/editable-select/jquery-editable-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery-bundle.min.css" integrity="sha512-nUqPe0+ak577sKSMThGcKJauRI7ENhKC2FQAOOmdyCYSrUh0GnwLsZNYqwilpMmplN+3nO3zso8CWUgu33BDag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop

@section ('footer')
<script src="/js/jquery.autocomplete.min.js"></script>
<script src="/js/jquery.mask.js" type="text/javascript"></script>
<script src="/js/editable-select/jquery-editable-select.js"></script>
@stop

    <!-- Berdvaye product-item -->
    <div wire:ignore.self id="slideover-model-container" class="fixed inset-0 w-full h-full invisible z-[51]" >
        <div wire:ignore.self id="slideover-model-bg" class="absolute duration-500 ease-out transition-all inset-0 w-full h-full bg-gray-900 opacity-0"></div>
        <div x-data="{preview: null}" tabindex="0" wire:ignore.self id="slideover-model" class="border absolute duration-500 ease-out transition-all h-full bg-white right-0 top-0 translate-x-full md:w-[665px] w-[390px] overflow-y-auto dark:bg-gray-800">
            <div class="bg-gray-200 dark:bg-gray-600 dark:text-gray-300 p-3 text-2xl text-gray-500">
                @if ($productId)
                    Edit Item
                @else
                    New Item
                @endif
            </div>
            <div @click="isSliderVisible = false; preview=null" id="slideover-model-child" class="p-2 flex items-center shadow-sm rounded-full justify-center hover:bg-gray-300 cursor-pointer absolute top-0 right-0 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div id="default-styled-tab-content" class="dark:bg-gray-800">
                <div class="p-4 rounded-lg dark:bg-gray-800" id="product" role="tabpanel" aria-labelledby="product-tab">
                    <form x-data="{ productid: @entangle('productId') }">
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

                        <input type="file" x-ref="fileInput" class="dark:text-gray-200" wire:model="photo" :class="{'hidden' : productid != 0}"
                            @change="const file = $event.target.files[0]; if (file) { preview = URL.createObjectURL(file); }">

                        @if (isset($item['image_location']))
                        <?php $img = $item['image_location']; ?>
                        @else
                        <?php $img =''; ?>
                        @endif

                        <div class="border mt-4 mb-4 shadow-sm flex justify-center p-2 bg-gray-100 dark:bg-gray-700"
                            @click="$refs.fileInput.click()" >

                            <img :src="preview ? preview : '{{$img}}'" class="w-52" alt="">
                        </div>

                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                        <x-input-standard model="item.p_model" label="p_model" position="uppercase" text="Model Number" validation />
                        <div class="grid gap-2 mb-2 md:grid-cols-2">
                            <x-input-standard model="item.model_name" label="model_name" text="Model Name" validation />
                            <x-input-standard model="item.p_retail" label="p_retail" text="Retail Price" validation />
                            <x-input-standard model="item.heighest_serial" label="heighest_serial" text="Heighest Serial"  />
                            <x-input-standard model="item.total_parts" label="total_parts" text="Total Parts"  />
                            <x-input-standard model="item.size" label="size" text="Size"  />
                            <x-input-standard model="item.weight" label="total_weight" text="Total Weight"  />
                            <x-input-standard model="item.dimensions" label="dimensions" text="Dimensions"  />
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" wire:model="item.is_active" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Show on Website?</label>
                            </div>
                        </div>

                        <label for="description" class="block text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="description" wire:model="item.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>

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
            </div>

        </div>
    </div>

@script
    <script>
        $(function() {

            // var isSliderVisible = false;

            function Slider() {
                $('body').toggleClass('overflow-hidden')
                $('#slideover-model-container').toggleClass('invisible')
                $('#slideover-model-bg').toggleClass('opacity-0')
                $('#slideover-model-bg').toggleClass('opacity-20')
                $('#slideover-model').toggleClass('translate-x-full')
                if (!$('#slideover-model-container').hasClass('invisible')) {
                    setTimeout(() => {
                        $('#p_model').focus();
                    }, "400");

                } else {
                    $('#table-search').focus();
                }
            }

            $wire.on('newProductData', msg => {
                Livewire.dispatch('refresh');
            })

            $wire.on('invoke-model', msg => {
                if (typeof msg[0].hide === typeof undefined)
                    hide = 1
                else hide = msg[0].hide

                if (hide==1)
                    Slider()

            });

            $(document).on('click', '.editproduct', function() {
                if ($('#slideover-model-container').hasClass('invisible')==true) {
                    $wire.$call("editItem", $(this).attr('data-id'))

                    Slider()

                    if (!$('#slideover-model-container').hasClass('invisible')) {
                        $('#slideover-model-container').css('z-index',51)
                            $('#products-tab').hide();
                    } else {
                        $('#products-tab').show();
                        const productTabButton = document.getElementById('product-tab');
                        if (productTabButton) {
                            productTabButton.click();
                        }
                    }
                }
            })

            window.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (!$('#slideover-model-container').hasClass('invisible')) {
                        $('#modelsearchbox').focus();
                        closeFields();
                    }
                }
            });

            window.closeFields = function() {
                Slider()
                $wire.$call('clearFields')
            }

            $('#slideover-model-child').click(function() {
                closeFields();
            })

        })
    </script>
@endscript

</div>