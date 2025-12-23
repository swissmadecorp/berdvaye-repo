@extends ("layouts.default-new")  

@section('content')

    <!-- Page Logo -->
    <section>
        <div class="items-center max-w-6xl mx-auto p-8 pt-2 relative">
            <img class="w-full" src="assets/design/design.jpg" alt="Design & Preparation">
            <div class="absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2 text-center whitespace-nowrap">
                <h2 class="font-playfair text-2xl sm:text-2xl md:text-4xl lg:text-[4rem] xl:text-6xl text-cream dark:text-cream">DESIGN & SOURCING</h2>
                <div class="mt-6 text-sm dark:text-white"><span><a href="/welcome">HOME</a></span> | <span>DESIGN & PREPARATION</span></div>
            </div>
        </div>
    </section>

    <!-- Page Description -->
    <section>
        <p class="max-w-4xl mx-auto p-4 text-center text-gray-400">Once deconstructed, the timepieces and parts are then painstakingly reimagined over one month of design work to expose the tremendous intricacies and human ingenuity involved in fine watchmaking.</p>
    </section>

    <section>
        <div class="max-w-5xl mx-auto py-12 950px:bg-[url('/assets/snake-design.png')] bg-no-repeat bg-center" style="background-size: 38% 72.5rem;">
            <div class="950px:grid 950px:grid-cols-3 flex">
                <div></div>
                <div>
                    <img class="rounded-[78px]" src="assets/design/image_00.png" alt="">
                </div>
                <div class="flex flex-col gap-2 justify-center pl-[2.75rem]">
                    <h2 class="uppercase text-xs">Selection & Grading</h2>
                    <h1 class="uppercase text-2xl text-darkred font-playfair">Selection & Grading</h1>
                    <p class="text-sm dark:text-white text-gray-500">Each and every gear, barrel, wheel, spring, escapement wheel, pinion, bridge, and hands is storied. The vast majority of the components are of Swiss origin. Each part is individually and carefully reviewed similar to the process of selecting and grading the most precious diamonds.</p>
                </div>
            </div>

            <div class="950px:grid 950px:grid-cols-3 flex pt-[2.75rem]">
                <div class="flex flex-col gap-2 justify-center pr-[2.75rem]">
                    <h2 class="uppercase text-xs">Positioning in Lucite®</h2>
                    <h1 class="uppercase text-2xl text-darkred font-playfair">Positioning in Lucite®</h1>
                    <p class="text-sm dark:text-white text-gray-500">Components are restored, polished and meticulously positioned, balanced in precious, crystal clear shatter-resistant Lucite®.</p>
                </div>
                
                <div>
                    <img class="rounded-[78px]" src="assets/design/image_01.png" alt="">
                </div>
                <div></div>
            </div>

            <div class="950px:grid 950px:grid-cols-3 flex pt-[2.75rem]">
                <div></div>
                <div>
                    <img class="rounded-[78px]" src="assets/design/image_02.png" alt="">
                </div>
                <div class="flex flex-col gap-2 justify-center pl-[2.75rem]">
                    <h2 class="uppercase text-xs">Placement & Baking</h2>
                    <h1 class="uppercase text-2xl text-darkred font-playfair">Placement & Baking</h1>
                    <p class="text-sm dark:text-white text-gray-500">The artisans place the components with a highly controlled method, ensuring total balance of size and color within the piece. Each sculpture then begins an arduous process of repeatedly placing components in layers of Lucite®, and then baking in highly pressurized 1,000° Oracle ovens.</p>
                </div>
            </div>

        </div>
    </section>

@endsection