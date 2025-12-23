<div> 
    
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt --> 
    <div x-data wire:ignore.self id="slideover-dealer-container" class="fixed inset-0 w-full h-full invisible z-50">
        <div wire:ignore.self id="slideover-dealer-bg" class="absolute duration-500 ease-out transition-all inset-0 w-full h-full bg-gray-900 opacity-0 "></div>
        <div wire:ignore.self id="slideover-dealer" class="absolute duration-500 ease-out transition-all h-full bg-white right-0 top-0 translate-x-full overflow-y-scroll dark:bg-gray-900 border" style="width: 700px">
            <div class="bg-gray-200 p-3 dark:bg-gray-600 dark:text-gray-300 text-2xl text-gray-500">
                @if ($dealerId)
                    Edit Dealer
                @else
                    New Dealer
                @endif
                
            </div>
            <div id="slideover-dealer-child" class="w-10 h-10 flex items-center shadow-sm rounded-full justify-center hover:bg-gray-300 cursor-pointer absolute top-0 right-0 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            
            <div class="p-6">
                <div class="relative">
                    <!-- <div class="absolute top-0 right-0"><button wire:click="lookupByName()" type="button" class="bg-black dark:bg-yellow-600 dark:focus:ring-yellow-800 dark:hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium hover:bg-gray-800 mr-2 mt-6 px-1 py-1 text-center text-sm text-white">Look Up</button></div> -->
                    <x-input-standard model="dealer.customer" label="customer" text="Dealer Name" validation />
                    @if (isset($dealer['companies']))
                    <!-- <div>
                        <select>
                        <option value="">-- Select Company --</option>
                            @foreach($dealer['companies'] as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div> -->
                    @endif
                </div>

                <div class="relative">
                    <div class="absolute top-0 right-0"><button wire:click="lookUpCoordinates()" type="button" class="bg-black dark:bg-yellow-600 dark:focus:ring-yellow-800 dark:hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium hover:bg-gray-800 mt-10 px-1 py-1 text-center text-sm text-white">Look Up</button></div>
                    <div class="pb-2.5">
                        <div :class="{'flex items-center': false }" class="pt-4">
                            <label for="address" :class="{'w-32': false }" class="block font-medium text-sm text-gray-900 dark:text-white ">Address</label>
                            <textarea id="address" wire:model="dealer.address" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid gap-2 mt-2 md:grid-cols-2">
                    
                    <x-input-standard model="dealer.website" label="website" text="Website" />
                    <x-input-standard model="dealer.phone" label="phone" text="Phone" />
                    <x-input-standard model="dealer.lat" label="lat" text="Lat" />
                    <x-input-standard model="dealer.lng" label="lng" text="Lng" />
                </div>

                <div class="flex justify-end">
                    @if ($DealerId)
                        <button wire:click="saveDealer()" type="button" class="text-white mt-4 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Update Dealer</button>
                    @else
                        <button wire:click="saveDealer()" type="button" class="text-white mt-4 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Save Dealer</button>
                    @endif
                </div>

                <hr>
                <livewire:invoice-item />

            </div>
        </div>

    </div>
@script
    <script> 
        $(function() {
            function Slider() {
                // debugger
                $('body').toggleClass('overflow-hidden')
                $('#slideover-dealer-container').toggleClass('invisible')
                $('#slideover-dealer-bg').toggleClass('opacity-0')
                $('#slideover-dealer-bg').toggleClass('opacity-75')
                $('#slideover-dealer').toggleClass('translate-x-full')
            }

            $(document).on('click', '.editdealer', function() {
                $wire.$call('clearFields');
                Slider()
            })

            $(document).on('click', '#slideover-dealer-child', function() {
                Slider()
            })

            $wire.on('display-message', msg => {
                Slider()
            });

        })
    </script>
@endscript
    
</div>