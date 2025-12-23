<div x-data="{ isSliderVisible: false,
        selectedRow: null,
        focusSearchBox() {
            if (!this.isSliderVisible) {
                $refs.searchbox.focus();
                $refs.searchbox.select();
            }
        }
            }"
x-init="focusSearchBox()"
@keydown.window="
if ($event.key === '=') {

            if ($refs.searchbox !== document.activeElement && !isSliderVisible) {
                $event.preventDefault();
                focusSearchBox();
            }
        }">
@section('main_header')
<!-- <link href="/css/dropzone.css" rel="stylesheet"> -->
<link href="/js/editable-select/jquery-editable-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery-bundle.min.css" integrity="sha512-nUqPe0+ak577sKSMThGcKJauRI7ENhKC2FQAOOmdyCYSrUh0GnwLsZNYqwilpMmplN+3nO3zso8CWUgu33BDag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop

@section ('footer')
<script src="/js/jquery.autocomplete.min.js"></script>
<script src="/js/editable-select/jquery-editable-select.js"></script>
<script src="/js/jquery.mask.js" type="text/javascript"></script>
@stop

    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

    <!-- Page Header -->
        @section ('button-menu')
        <div class="relative">
            <button id="actions" data-dropdown-toggle="dropdown" class="mt-1 mr-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Actions<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

            <!-- Dropdown menu -->

            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="absolute top-0 left-0 bg-gray-200 py-2 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-200" aria-labelledby="dropdownglobalpricerButton">
                    <li>
                        <button id="exportproducts-modal" onclick='window.location.href="products/1/export";' type="button" class="text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white w-full">Export Products</button>
                    </li>
                </ul>

            </div>
        </div>
    @stop

    @if (session()->has('message'))
        <div id="alert-border-1" class="flex items-center p-4 mb-4 text-blue-800 border-t-4 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800 transition-all duration-500 animate-bounce" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium">
                @if (is_array(session('message')))
                    {{ session('message')['msg'] }}
                @else
                    {{ session('message') }}
                @endif
            </div>
            <button type="button" @click="dismiss()" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-border-1" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            </button>
        </div>
    @elseif (session()->has('error'))
        <div id="alert-border-1" class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800 transition-all duration-500 animate-bounce" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium">
                {{ session('error') }}
            </div>
            <button type="button" @click="dismiss()" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-2" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            </button>
        </div>
    @endif


    <livewire:product-item />
    <livewire:invoice-item />

    <div class="sm:rounded-lg" >
        <div class="flex items-center justify-between flex-column md:flex-row flex-wrap space-y-4 md:space-y-0 py-4 p-2 bg-white dark:bg-gray-900">
            @role('superadmin|administrator')
                <button @click="isSliderVisible = !isSliderVisible" id="newproduct" class="bg-sky-500 hover:bg-[#0284c7] text-white font-bold text-sm px-3 py-1.5 rounded">Create New</button>
            @endrole
                <div class="bg-gray-50 block border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:border-blue-500 dark:focus:ring-blue-500 dark:placeholder-gray-400 dark:text-white focus:border-blue-500 focus:ring-blue-500 mt-1 ps-10 relative rounded-lg text-gray-900 w-2 w-96">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" x-ref="searchbox" wire:model.live.debounce.5s="search" id="table-search" class="focus:ring-0 bg-gray-50 border-0 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white h-10 rounded-lg text-gray-900 w-52" placeholder="Search for items">
                    <!-- wire:change="$event.target.value" -->
                    <select wire:model.live="status" class="absolute bg-gray-50 block border- border-0 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white focus:ring-0 p-2 right-0 text-gray-900 text-sm" style="top: 1px;top: 1px;border-left: 1px solid #cdcccc;">
                        <?php $stats = [6,8,10]?>
                        @foreach (Status() as $key => $status)
                            @if (!in_array($key,$stats))
                            <option <?php echo !empty($product->p_status) && $product->p_status==$key ? 'selected' : '' ?> value="{{ $key }}">{{ $status }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
        </div>

        <ul class="bg-white border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-medium items-center mb-1 mt-1 sm:flex text-gray-900 text-sm w-full">
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center ps-3">
                    <input id="radio-onhand1" wire:click="$set('onhand',1)" type="radio" <?= ($onhand==1) ? 'checked' : "" ?> name="list-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="radio-onhand1" class="py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Display On-Hand </label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center ps-3">
                    <input id="radio-onhand2" wire:click="$set('onhand',0)" <?= ($onhand==0) ? 'checked' : "" ?> type="radio" name="list-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="radio-onhand2" class="py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Display Not On-Hand</label>
                </div>
            </li>
        </ul>

                <!-- wire:poll.15s.visible -->
            <!-- Popup Menu (Hidden Initially) -->
        @role('superadmin|administrator')
        <div id="popup-menu" class="hidden z-50 absolute bg-gray-200 dark:bg-gray-800 shadow-lg rounded-lg border w-44 border border-gray-300">
            <ul class="divide-gray-300">
                <li wire:ignore.self class="menu-item cursor-pointer block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white editinvoice">Make Invoice</li>
                <li wire:confirm = "Are you sure you want to delete this item?" class="menu-item border-t border-gray-300 cursor-pointer block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white deleteitem">Delete Item</li>
            </ul>
        </div>
        @endrole

        <div class="overflow-x-auto relative ">
            <table class="w-full text-sm text-left rtl:text-right dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" style="width: 40px" class="px-3 py-3"></th>
                        <th scope="col" style="width: 40px" class="px-3 py-3">Id</th>
                        <th scope="col" class="px-8 py-3">Image</th>
                        <th scope="col" class="cursor-pointer w-[200px] px-3 py-3" wire:click="doSort('model_name')">
                        <x-product-dataitem :sortBy="$sortBy" :sortDirection="$sortDirection" columnName="model_name" displayName="Model Name" />
                        </th>
                        <th scope="col" class="cursor-pointer px-3 py-3" wire:click="doSort('p_serial')">
                            <x-product-dataitem :sortBy="$sortBy" :sortDirection="$sortDirection" columnName="p_serial" displayName="Serial #" />
                        </th>
                        <th scope="col" class="px-3 py-3">Cost</th>
                        <th scope="col" class="px-3 py-3">Retail</th>
                        <th scope="col" class="px-3 py-3">Qty</th>
                        <th scope="col" class="px-3 py-3">Date</th>
                    </tr>
                </head>
                <tbody>

                <?php $counter = 0 ?>

                @foreach($products as $product)
                    <tr class="odd:bg-white hover:bg-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-3 py-2 relative text-center">
                            <!--Under views/components/input.blade.php -->
                            <button type="button" data-id="{{$product->id}}" class="menu-btn inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            <svg class="w-2.5 h-2.5 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                            Options
                        </button>
                        </td>
                        <td class="px-3 py-2"><a  @click="isSliderVisible = true; selectedRow = {{$loop->index}}" data-id="{{$product->id}}" class="editproduct cursor-pointer hover:text-blue-500 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 dark:hover:text-white">{{ $product->id }}</a></td>
                        <td class="px-3 py-2 w-24"><img class="w-24 justify-center" src="{{$product->image()}}" /></td>
                        <td class="px-3 py-2">
                            <div class="flex justify-between relative">
                                {{ $product->retail->model_name }}
                                @if ($product->p_status > 0)
                                <span class="-top-6 absolute bg-green-500 mr-1 p-0.5 right-0 rounded text-white text-xs pt-0">{{Status()->get($product->p_status)}}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-2">{{ $product->p_serial}}</td>
                        <td class="px-3 py-2">${{ number_format($product->retail->p_retail / 2, 2)}}</td>
                        <td class="px-3 py-2">${{ number_format($product->retail->p_retail,2)}}</td>
                        <td class="px-3 py-2">{{ $product->p_qty}}</td>
                        <td class="px-3 py-2">{{ $product->created_at->format('m/d/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold text-gray-900 dark:text-white">
                        <th colspan="6" scope="row" class="px-3 py-3 text-base">Total</th>
                        <td colspan="1" class="px-3 py-3"><span class="text-red-800 dark:text-red-300">${{number_format($totalCost,0)}}</span></td>
                        <td class="px-3 py-3 text-center"><span class="text-red-800 dark:text-red-300">{{$totalQty}}</span></td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="bg-gray-50 rounded-lg dark:bg-gray-800">
       {{ $products->links('livewire.pagination') }}
    </div>
    @endif

@section ('jquery')
<script>
    $(document).ready( function() {
        $(document).on('mouseenter', 'span.hide', function () {
            $(this).css('opacity',1)
        }).on('mouseleave', 'span.hide', function () {
            $(this).css('opacity',0)
        })

        $(document).on("click", ".menu-btn", function (e) {
            e.stopPropagation(); // Prevent closing when clicking the button

            let menu = $("#popup-menu");
            let button = $(this);
            let productId = button.data("id"); // Get product ID from button

            menu.find(".menu-item").attr("data-id", productId);
            menu.find("li.editinvoice").attr("wire:click.prevent", `makeInvoice(${productId})`);
            menu.find("li.deleteitem").attr("wire:click", `deleteProduct(${productId})`);

            let buttonOffset = button.offset();
            let buttonHeight = button.outerHeight();
            let menuHeight = menu.outerHeight();
            let windowHeight = $(window).height();
            let scrollTop = $(window).scrollTop(); // Get scroll position

            let spaceBelow = windowHeight + scrollTop - (buttonOffset.top + buttonHeight);
            let spaceAbove = buttonOffset.top - scrollTop;

            let topPosition;

            // Show below if there's space, otherwise show above
            if (spaceBelow >= menuHeight || spaceBelow > spaceAbove) {
                topPosition = buttonOffset.top + buttonHeight + 5;
            } else {
                topPosition = buttonOffset.top - menuHeight - 5;
            }

            // Check if the menu is already open for the same button
            if (menu.is(":visible") && menu.data("active-button") === button[0]) {
                menu.addClass("hidden").removeData("active-button"); // Hide menu
            } else {
                menu.css({
                    top: topPosition + "px",
                    left: buttonOffset.left + "px",
                }).removeClass("hidden").data("active-button", button[0]); // Show menu and store active button
            }
        });

        // Hide menu when clicking anywhere outside
        $(document).on("click", function () {
            $("#popup-menu").addClass("hidden").removeData("active-button");
        });
    })

</script>

@endsection
</div>