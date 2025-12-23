<?php $totals=0; ?>
<div>
    <div wire:ignore.self id="slideover-product-container" class="fixed inset-0 w-full h-full invisible z-60" >
        <div wire:ignore.self id="slideover-product-bg" class="absolute duration-500 ease-out transition-all inset-0 w-full h-full bg-gray-900 opacity-0"></div>
        <div @keydown.escape.prevent="closeAndClearProductFields()" wire:ignore.self id="slideover-product" class="absolute duration-500 ease-out transition-all h-full bg-white right-0 top-0 translate-x-full overflow-x-hidden overflow-y-scroll xm:w-[54rem] w-[305px]">

            <div wire:loading.flex wire:target="startPaymentProcess" class="absolute h-full w-full flex items-center justify-center z-101 bg-black/80" >
                <div class="text-white text-lg flex items-center space-x-2">
                    <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Processing payment...</span>
                </div>
            </div>

            <div id="maincontainer" class="bg-gray-200 p-3 text-2xl text-gray-500 flex item-center gap-2">
                <button id="backbutton" wire:click.prevent="previousStep" class="{{($showFormInputs) ? 'block' : 'hidden'}}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                    </svg>
                </button>

                <span>Cart</span>
            </div>
            <div id="slideover-product-child" class="text-gray-900 w-10 h-10 flex items-center shadow-2xs rounded-full justify-center hover:bg-gray-300 cursor-pointer absolute top-0 right-0 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            <div>

                @inject('countries','App\Libs\Countries')
                <div class="h-screen relative xm:flex xm:w-full w-[318px]">
                    <!-- Big Div -->
                    <div wire:ignore.self id="bigDiv" class="xm:w-[70rem] shrink-0 transition-transform duration-500">
                        <div class="flex">
                            <div class="xm:w-[565px] w-[305px]">
                                <div class="flex justify-between text-gray-500 p-2 font-bold text-lg">
                                    <span>Shopping Cart</span>
                                    <span>{{$countCart}} Items</span>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="xm:min-w-[490px] w-full text-sm text-left text-gray-900">
                                        <thead class="bg-gray-50 text-xs">
                                        <tr class="text-gray-500">
                                            <th class="py-3 px-4">Item</th>
                                            <th class="py-3 px-4">Size</th>
                                            <th class="py-3 px-4">Qty</th>
                                            <th class="py-3 px-4">Price</th>
                                            <th class="py-3 px-4"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($cartproducts) && count($cartproducts))
                                            @foreach ($cartproducts as $product)
                                            <tr wire:key="cart-item-{{ $product['id'] }}" class="bg-white text-gray-900">
                                                <td data-label="Image" class="py-3 w-[190px]">
                                                    <div class="flex gap-2 items-center w-44">
                                                        <a href="/products/{{$product['model_name']}}"><img src="{{$product['image']}}" class="w-[58px]"></a>
                                                        <span>{{$product['model_name']}}</span>
                                                    </div>
                                                </td>
                                                <td data-label="Size:" class="py-3 text-left px-3 align-middle text-left">
                                                    <span>{{$product['size']}}</span>
                                                </td>
                                                <td class="align-middle py-3 px-4">
                                                    <div class="relative flex items-center">
                                                        <button wire:ignore.self wire:click.prevent="setNewQty('{{ $product['p_model'] }}', {{$product['id']}}, 'remove')" type="button" id="decrement-button-{{$product['id']}}" data-input-counter-decrement="counter-input-{{$product['id']}}" class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary rounded-full text-sm focus:outline-none h-6 w-6">
                                                            <svg class="w-3 h-3 text-heading" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>
                                                        </button>
                                                        <input type="text" id="counter-input-{{$product['id']}}" data-input-counter-min="1" data-input-counter-max="{{$product['available']}}" data-input-counter class="shrink-0 text-heading border-0 bg-transparent text-sm font-normal focus:outline-none focus:ring-0 max-w-[2.5rem] text-center" placeholder="" value="{{ $product['qty'] }}" required />
                                                        <button wire:ignore.self wire:click.prevent="setNewQty('{{ $product['p_model'] }}', {{$product['id']}}, 'add')" type="button" id="increment-button-{{$product['id']}}" data-input-counter-increment="counter-input-{{$product['id']}}" class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary rounded-full text-sm focus:outline-none h-6 w-6">
                                                            <svg class="w-3 h-3 text-heading" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-left py-3 px-4">${{number_format($product['price'],2)}}</td>
                                                <td class="align-middle text-center py-3 px-4">
                                                    <button wire:click.prevent="removeItemFromCart({{$product['id']}})" class="cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-red-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php $totals +=$product['price'] * $product['qty'] ?>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                    <div class="pr-3 pl-3 text-darkred text-sm">{{ $productQtyErrorMessage }}</div>
                                </div>
                            </div>

                            <div class="text-gray-500 p-2 font-bold text-lg xm:w-1/2 pl-2 w-full xm:pl-2" id="form-column">

                                <form id="formInputs" @class(['hidden' => !$showFormInputs])>
                                    <span class="pb-3 block">Shipping Details</span>
                                    <div class="xm:w-auto xm:w-[300px] w-[290px] xm:pl-3">
                                        <div>
                                            <input wire:model="customer.email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Email" required />
                                            @error("customer.email")
                                                <span class="text-red-500">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="grid gap-4 mb-6 xm:grid-cols-2 mt-4">
                                            <div>
                                                <input id="firstname" wire:model="customer.firstname" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="First Name" required autocomplete/>
                                                @error("customer.firstname")
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <input id="lastname" wire:model="customer.lastname" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Last Name" required autocomplete />
                                                @error("customer.lastname")
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <input id="phone" wire:model="customer.phone" type="tel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required autocomplete />
                                                @error("customer.phone")
                                                    <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <input id="company" wire:model="customer.company" type="tel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Company" required autocomplete />
                                            <div>
                                                <input id="address1" wire:model="customer.address1" type="tel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Address 1" required  autocomplete />
                                                @error("customer.address1")
                                                    <span class="text-red-500">{{$message}}</span>
                                                    @enderror
                                            </div>
                                            <input id="address2" wire:model="customer.address2" type="tel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Address 2" required autocomplete />
                                            <div>
                                                <input id="bcountry" type="hidden" value="{{$customer['card-billing-address-country-code']}}">
                                                <select id="country" wire:model.live="selectedBCountry" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    @foreach($this->countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("selectedBCountry")
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <input id="city" wire:model="customer.city" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="City" required />
                                                @error("customer.city")
                                                    <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <select id="state" wire:model.live="selectedBState" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option>Select option</option>
                                                    @foreach($this->billingStates as $state)
                                                        <option value="{{ $state->id }}" {{ (string) $state->id === (string) $this->selectedBState ? 'selected' : '' }}>
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("selectedBState")
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <input id="zip" wire:model="customer.zip" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-400" placeholder="Postal Code" required />
                                                @error("customer.zip")
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <span>Payment Method</span>

                                    <div id="paypal-container" wire:ignore class="xm:ml-3">
                                        <div id="paypal-button-container" class="paypal-button-container"></div>

                                        <div id="card-form" class="card_container">
                                            <div id="card-number-field-container"></div>
                                            <div id="card-expiry-field-container"></div>
                                            <div id="card-cvv-field-container"></div>
                                        </div>

                                        <p id="result-message"></p>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- Small Div -->
                    <div class="xm:absolute bg-gray-100 text-white xm:h-full xm:right-0 xm:top-0 xm:w-1/3">
                        <div class="bg-gray-100 font-bold xm:w-[25rem] p-2 pr-2 xm:w-auto w-[310px]">

                            <span class="block text-gray-500 text-lg">Summary</span>
                            <table class="table w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-400">
                                <tr>
                                    <td class="py-3 px-4">Subtotal</td>
                                    <td class="py-3 px-4 text-right">${{ number_format($totals,2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">Shipping</td>
                                    <td class="py-3 px-4 text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">Tax <?= isset($customer['tax']) ? $customer['tax'] : '' ?> </td>
                                    <td class="py-3 px-4 text-right">
                                        @if (isset($customer['taxamount']))
                                            ${{$customer['taxamount']}}
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <div class="p-2 mt-8">
                                <label for="promocode" class="block mb-2 font-bold text-gray-500 text-lg">Promocode</label>
                                <div class="border border-[#e5b384] flex justify-between">
                                    <input type="text" id="promocode" placeholder="Enter Promo Code" class="bg-gray-50 border-0 focus:ring-0 outline-hidden p-2.5 text-gray-900 text-sm w-full" />
                                    <button class="bg-gray-800 text-white p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="p-2 xm:mt-32 flex justify-between font-bold text-lg text-gray-500">
                                <span>Total</span>
                                <span>{{$grandtotal}}</span>
                            </div>

                            <div class="pl-1 xm:pr-0">
                                <button wire:ignore.self type="button" id="checkout" wire:click.prevent="goToNextStep" class="cursor-pointer w-full transition-colors duration-300 ease-in-out px-6 py-2 text-sm bg-darkred dark:text-cream rounded-full hover:bg-red-700">Checkout</button>
                                <button wire:ignore.self type="button" id="paynow" wire:click="startPaymentProcess"
                                    class="cursor-pointer w-full transition-colors duration-300 ease-in-out px-6 py-2 text-sm bg-darkred dark:text-cream rounded-full hover:bg-red-700 hidden">
                                        Pay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push ('jquery')
        <script src="https://www.paypal.com/sdk/js?client-id={{config('paypal.live.client_id')}}&components=buttons,messages,card-fields&disable-funding=paylater"></script>
        <script src="/js/paypal-new.js"></script>

        <!-- <script type="text/javascript">
            jQuery.noConflict();
            jQuery(document).ready(function($) {

                // $('.menuBar div.rightMenu').append('<img class="imgCenter" src="/images/menu/menu_banner_01.jpg" alt="menu_banner_01">');
                $.QueryString = (function(paramsArray) {
                    let params = {};

                    for (let i = 0; i < paramsArray.length; ++i)
                    {
                        let param = paramsArray[i]
                            .split('=', 2);

                        if (param.length !== 2)
                            continue;

                        params[param[0]] = decodeURIComponent(param[1].replace(/\+/g, " "));
                    }

                    return params;
                })(window.location.search.substr(1).split('&'))

                var qs = $.QueryString["a"];

                if (qs) {
                    $.ajax({
                        type: "post",
                        data: {id: qs,_token: '{{csrf_token()}}'},
                        url: "{{route('add.to.cart')}}",
                        success: function (result) {
                            // if (isMobile())
                            //     document.location.href = '/cart';
                            // else {
                                //if ($('.cart-anim').length>0) {
                                $('#cart').html(result);
                                $('html,body').animate({ scrollTop: 0 }, 'slow');
                                    //$('.cart-anim').addClass('move-to-cart')

                                // setTimeout(function(){ window.location.reload(); }, 500);
                                //}
                            //}
                        }
                    })
                }

            });
        </script> -->
    @endpush

    @script
        <script type="text/javascript">
        jQuery.noConflict();
        jQuery(document).ready(function ($) {

            const DESKTOP_TRANSLATE = "-translate-x-[565px]";
            const MOBILE_TRANSLATE  = "-translate-x-[305px]";

            // BACK
            $('#backbutton').on('click', function () {
                debugger
                if (window.matchMedia("(max-width: 876px)").matches) {
                    $('#formInputs').addClass('hidden');
                    $("#bigDiv").removeClass(MOBILE_TRANSLATE);
                } else {
                    $("#bigDiv").removeClass(DESKTOP_TRANSLATE);
                }

                $('#checkout').removeClass('hidden');
                $('#paynow').addClass('hidden');
            });

            // CHECKOUT
            $(document).on('click', '#checkout', function () {
                if (window.matchMedia("(max-width: 876px)").matches) {
                    $("#bigDiv")
                        .removeClass(DESKTOP_TRANSLATE)
                        .addClass(MOBILE_TRANSLATE);
                    $('#formInputs').removeClass('hidden');
                } else {
                    $("#bigDiv")
                        .removeClass(MOBILE_TRANSLATE)
                        .addClass(DESKTOP_TRANSLATE);
                }

                $('#checkout').addClass('hidden');
                $('#paynow').removeClass('hidden');

                setTimeout(() => $('#email').focus(), 600);

                $("#maincontainer")[0].scrollIntoView({
                    behavior: "smooth",
                    block: "center", // Adjust as needed: "start", "center", "end", "nearest"
                });
            });

            // RESIZE FIX
            function handleResize() {
                if (window.matchMedia("(max-width: 876px)").matches) {
                    if ($("#bigDiv").hasClass(DESKTOP_TRANSLATE)) {
                        $("#bigDiv")
                            .removeClass(DESKTOP_TRANSLATE)
                            .addClass(MOBILE_TRANSLATE);
                    }
                } else {
                    if ($("#bigDiv").hasClass(MOBILE_TRANSLATE)) {
                        $("#bigDiv")
                            .removeClass(MOBILE_TRANSLATE)
                            .addClass(DESKTOP_TRANSLATE);
                    }
                }
            }

            $(window).on('resize', handleResize);
            handleResize();

            // SLIDER OPEN / CLOSE
            function Slider() {
                $('body').toggleClass('overflow-hidden');
                $('#slideover-product-container').toggleClass('invisible');
                $('#slideover-product-bg').toggleClass('opacity-0 opacity-20');
                $('#slideover-product').toggleClass('translate-x-full');
            }

            // LIVEWIRE EVENTS
            // $wire.on('go-to-checkout-step-one', () => {
            //     $('#paypal-container').addClass('hidden');
            // });

            // $wire.on('go-to-checkout-step-two', () => {
            //     $('#paypal-container').removeClass('hidden');
            // });

            $wire.on('dispatched-message', msg => {
                if (msg[0].msg === 'deleteproduct' && $wire.$get('countCart') === 0) {
                    $wire.$dispatch('clear-form');
                    Slider();
                }
                if (msg[0].msg === 'addtocart' && $wire.$get('countCart') > 0) {
                    Slider();
                }
            });

            $(document).on('click', '.total-cart-pro', function () {
                $wire.dispatch('refreshCart');
                $('#backbutton').click();
                Slider();
            });

            $('#slideover-product-child').on('click', function () {
                $wire.$dispatch('clear-form');
                $('#result-message').html('');
                Slider();
            });

        });
        </script>
    @endscript

</div>