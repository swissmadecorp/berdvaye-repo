<div x-data="{ isSliderVisible: false,
        focusSearchBox() {
            if (!this.isSliderVisible) {
                $refs.modelsearchbox.focus();
                $refs.modelsearchbox.select();
            }
        }
            }"
x-init="focusSearchBox()"
@keydown.window="
if ($event.key === '=') {

            if ($refs.modelsearchbox !== document.activeElement && !isSliderVisible) {
                $event.preventDefault();
                focusSearchBox();
            }
        }">

    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

    <livewire:model-item />
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

    <div class="sm:rounded-lg" >
        <div class="flex items-center justify-between flex-column md:flex-row flex-wrap space-y-4 md:space-y-0 py-4 bg-white dark:bg-gray-900 md:p-4">
                @role('superadmin|administrator')
                    <button @click="isSliderVisible = !isSliderVisible" wire:ignore.self class="editproduct bg-sky-500 hover:bg-[#0284c7] text-white font-bold text-sm px-3 py-1.5 rounded cursor-pointer">Create New</button>
                @endrole

                <div  wire:ignore class="relative mt-1">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" id="modelsearchbox" x-ref="modelsearchbox" wire:model.live.debounce.150ms="search" id="invoice-search" class="block h-10 ps-10 text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
                </div>
        </div>
                <!-- wire:poll.15s.visible -->
            <!-- Popup Menu (Hidden Initially) -->
        <div id="popup-menu" class="hidden z-50 absolute bg-gray-200 dark:bg-gray-800 shadow-lg rounded-lg border w-44 border border-gray-300">
            <ul class="divide-gray-300">
                <li wire:ignore.self class="menu-item cursor-pointer block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white editinvoice">Make Invoice</li>
                <li wire:confirm = "Are you sure you want to delete this item?" class="menu-item border-t border-gray-300 cursor-pointer block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white deleteitem">Delete Item</li>
            </ul>
        </div>

        <div class="overflow-x-auto relative ">
            <table class="w-full text-sm text-left rtl:text-right dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="cursor-pointer px-3 py-3" wire:click="doSort('p_model')">
                            <x-product-dataitem :sortBy="$sortBy" :sortDirection="$sortDirection" columnName="p_model" displayName="Reference" />
                        </th>
                        <th scope="col" class="px-3 py-3">Image</th>
                        <th scope="col" class="cursor-pointer px-3 py-3" wire:click="doSort('model_name')">
                            <x-product-dataitem :sortBy="$sortBy" :sortDirection="$sortDirection" columnName="model_name" displayName="Model Name" />
                        </th>

                        <th scope="col" class="px-3 py-3 text-left" wire:click="doSort('created_at')">
                            <x-product-dataitem :sortBy="$sortBy" :sortDirection="$sortDirection" columnName="created_at" displayName="Date" />
                        </th>
                        <th scope="col" class="px-3 py-3">Retail</th>
                        <th scope="col" class="px-3 py-3"></th>
                    </tr>
                </head>
                <tbody>

                <?php $counter = 0 ?>

                @foreach($products as $product)
                    <?php
                        $imagePath = "/images/gallery/thumbnail/". strtolower($product->p_model) .'_thumb.jpg';
                        if (file_exists(public_path($imagePath)))
                            $imageElem = $imagePath;
                    else
                    $imageElem = '/images/no-image.jpg';
                    ?>

                    <tr class="odd:bg-white hover:bg-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="py-2 w-32"><a  @click="isSliderVisible = true; selectedRow = {{$loop->index}}" data-id="{{$product->id}}" class="editproduct cursor-pointer hover:text-blue-500 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 dark:hover:text-white">{{$product->p_model}}</a></td>
                        <td class="px-3 py-2 w-24"><img class="w-24 justify-center" src="{{$imageElem}}" /></td>
                        <td class="px-3 py-2">{{ $product->model_name }}</td>
                        <td class="px-3 py-2 text-left">{{ $product->created_at->format('m/d/Y') }}</td>
                        <td class="px-4 py-2 text-right">${{ number_format($product->p_retail,2)}}</td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click= "deleteModel('{{$product->id}}')" wire:confirm="Are you sure you want to delete this model # {{$product->p_model}}" class="bg-red-300 hover:bg-red-500 text-black text-sm px-3 py-1.5 rounded cursor-pointer">Delete</button>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="bg-gray-50 rounded-lg dark:bg-gray-800 mt-10">
       {{ $products->links('livewire.pagination') }}
    </div>
    @endif

</div>