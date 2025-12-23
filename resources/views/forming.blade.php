@extends ("layouts.default-new")  

@section('content')

    <!-- Page Logo -->
    <section>
        <div class="items-center max-w-6xl mx-auto p-8 pt-2 relative">
            <img class="w-full" src="assets/forming/banner.jpg" alt="Forming & Finishing">
            <div class="absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2 text-center whitespace-nowrap">
                <h2 class="font-playfair text-2xl sm:text-2xl md:text-4xl lg:text-[4rem] xl:text-6xl text-cream">FORMING & FINISHING</h2>
                <div class="mt-6 text-sm"><span><a href="/welcome">HOME</a></span> | <span>FORMING & FINISHING</span></div>
            </div>
        </div>
    </section>

    <!-- Page Description -->
    <section>
        <p class="max-w-4xl mx-auto p-4 text-center text-gray-400">Innovatively preserving the history of watchmaking through a meticulous reconditioning and reinvention process.</p>
    </section>

    <section>
        <div class="max-w-5xl mx-auto py-12 950px:bg-[url('/assets/snake-forming.png')] bg-no-repeat bg-center" style="background-size: 38% 49.5rem;">
            <div class="950px:grid 950px:grid-cols-3 flex">
                <div></div>
                <div>
                    <img class="rounded-[78px]" src="assets/design/image_00.png" alt="">
                </div>
                <div class="flex flex-col gap-2 justify-center pl-[2.75rem]">
                    <h2 class="uppercase text-xs">Handcrafted with perfection</h2>
                    <h1 class="uppercase text-2xl text-darkred font-playfair">Handcrafted with perfection</h1>
                    <p class="text-sm dark:text-white text-gray-500">Each Berd Vay’e piece is casted with 8-10 layers over a 24 hour period. The sculpture is then baked, carefully protecting it from the formation of air bubbles or imperfections thus resulting in a seamless, crystal clear sculpture. The cooled Lucite® is shatter resistant, having heralded from its early use on submarines and helicopters. Each sculpture is then shaped and polished by hand, requiring relentless attention from multiple artisans to complete every one-of-a-kind piece.</p>
                </div>
            </div>

            <div class="950px:grid 950px:grid-cols-3 flex pt-[2.75rem]">
                <div class="flex flex-col gap-2 justify-center pr-[2.75rem]">
                    <h2 class="uppercase text-xs">Bespoke contemporary art</h2>
                    <h1 class="uppercase text-2xl text-darkred font-playfair">Bespoke contemporary art</h1>
                    <p class="text-sm dark:text-white text-gray-500">This process forever preserves the history in watchmaking through reinvention. The result is a visual effect of gears floating in the air, allowing collectors to have a 360 degree appreciation for details that are normally hidden behind watch dials. Berd Vay’e breathes new life into the appreciation of horology with distinctive, Bespoke conversation pieces like no contemporary art you’ve ever seen before.</p>
                </div>
                
                <div>
                    <img class="rounded-[78px]" src="assets/design/image_01.png" alt="">
                </div>
                <div></div>
            </div>

        </div>
    </section>

@endsection