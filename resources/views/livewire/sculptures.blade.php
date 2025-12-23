<div >
    @section ("title","Sculptures - Berd Vay'e Collection")

    <section>
        <div class="defaultBanner">

            <img class="w-full brightness-50" src="/assets/sculptures.jpg" alt="hb_00" />

            <!-- Keep same size but use background opacity instead of element opacity -->
            <div class="bannerBox-info md:bg-gray-300/30 hidden md:block bg-darkgray">
                <div class="text-4xl font-semibold text-cream-300">The Berdvay'e COLLECTION</div>
            </div>

        </div>
    </section>

    @php
        use Illuminate\Support\Str;
    @endphp

    @if ($products->count())
        <div id="product-items" class="max-w-6xl mx-auto">
            <div class="mx-auto p-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($products as $product)
                    <?php
                        $parsedPath = strtolower(str_replace(' ','-', $product->p_model));
                        $parsedModelName = strtolower(str_replace(' ','-', $product->model_name));
                        $img = 'assets/products/' . $parsedPath . '.jpg';
                    ?>

                    <a href="/sculptures/{{$parsedModelName}}"
                    wire:key="{{$product->id}}"
                    class="border border-dark bg-black hover:text-white group flex flex-col p-2 hover:bg-gray-900 rounded-lg transition duration-300 ease-in-out h-full">

                        <!-- Image (larger, fully visible) -->
                        <div class="w-full mb-2 flex justify-center items-center">
                            @if (!file_exists(base_path(). '/public/' . $img))
                                <img class="w-full max-h-64 object-contain" src="/images/no-image.jpg" alt="">
                            @else
                                <img class="w-full max-h-64 object-contain"
                                    title="{{ $product->model_name }}"
                                    alt="{{ $product->model_name }}"
                                    src="{{$img}}">
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="flex-1 flex flex-col justify-between mt-2">
                            <!-- Border -->
                            <div class="border border-dark mb-2"></div>

                            <!-- Description -->
                            <div class="transition-colors duration-200 ease-in-out truncate-4-lines">
                                <span class="font-playfair block text-lg mb-2 text-cream-300">
                                    {{ $product->model_name }}
                                </span>
                                <span class="block text-sm text-gray-300 line-clamp-20 leading-6">
                                    {{ Str::limit($product->description, 200) }}
                                </span>
                            </div>

                            <!-- Button at bottom -->
                            <div class="mt-auto pt-4">
                                <button class="bg-cream-300 font-semibold cursor-pointer duration-200 ease-in-out
                                            group-hover:bg-cream-400 leading-5 p-2 rounded-md
                                            text-gray-900 transition-colors w-full">
                                    @if($product->min_retail != $product->max_retail)
                                        ${{ number_format($product->min_retail) }} - ${{ number_format($product->max_retail) }}
                                    @else
                                        ${{ number_format($product->min_retail) }}
                                    @endif
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    @endif

    @push ('jquery')
        <script type="text/javascript">
            jQuery.noConflict();
            jQuery(document).ready(function($) {

                $('.collectionmenu').addClass('active');
                $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_01.jpg" alt="menu_banner_05">');

            });
        </script>
    @endpush
</div>
