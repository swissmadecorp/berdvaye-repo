<button id="menu-button" class="950px:hidden absolute p-2 left-0">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-900 dark:text-white">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
</button>

<nav id="mobile-menu">
    <ul>
        <li class="hover:text-cream"><a href="/welcome">Home</a></li>
        <li class="hover:text-cream"><a href="/sculptures">Shop</a></li>
        <li class="hover:text-cream"><a href="/process">Process</a></li>
        <li class="hover:text-cream"><a href="/">Retail</a></li>
        <li class="hover:text-cream"><a href="/">Contact Us</a></li>
    </ul>
</nav>

@livewire('cart-component') 
<!-- Header -->
<header class="950px:flex justify-between items-center p-4 950px:px-10 dark:bg-black bg-gray-100">
    <div class="flex font-bold justify-center text-3xl text-cream">
        <a href="\welcome"><img class="brightness-75 dark:brightness-100" src="/assets/berdvaye-logo.png" alt="BerdVay'e Inc."></a>
    </div>
    <nav class="hidden 950px:flex text-sm">
        <ul class="menu-items">
          <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">HOME</a></li>
          <!-- Shop -->
          <li class="dropdown z-10"> 
            <a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">SHOP</a>
            <ul class="dropdown-menu  w-[190px] dark:bg-darkgray bg-gray-100">
              <li><a href="\sculptures" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Chess Collection</a></li>
              <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Item 2</a></li>
              <li class="dropdown dropdown-right">
                <a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">
                  Item 3
                  <i class="fas fa-angle-right"></i>
                </a>
                <ul class="menu-right dark:bg-darkgray bg-gray-100">
                  <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Item 3.1</a></li>
                  <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Item 3.2</a></li>
                  <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Item 3.3</a></li>
                  <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Item 3.4</a></li>
                </ul>
              </li>
              <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">Item 4</a></li>
            </ul>
          </li>
          <!-- Process -->
          <li>
            <a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">PROCESS</a>
            <div class="mega-menu blog dark:bg-darkgray z-10 bg-gray-100">
              <div class="content">
                <div class="col">
                  <a href="\deconstruction" class="img-wrapper"
                    ><span class="img"
                      ><img
                        src="/images/homepage/forming_img_00.jpg"
                        alt="Random Image" /></span
                  ></a>
                  <h2 class="font-playfair">Deconstruction & Sourcing</h2>
                  <p class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream">
                  Expert curators fastidiously source 50-100 year-old timepieces and parts with a true passion for horology – from collectible 19th century backwind pocket watches, to remnants of 1900s Americana in the form of stop clocks once belonging to railroad conductors.
                  </p>
                  <a href="#" class="read-more">read more...</a>
                </div>
                <div class="col">
                  <a href="/design" class="img-wrapper"
                    ><span class="img"
                      ><img
                        src="/images/homepage/design_img_00.jpg"
                        alt="Random Image" /></span
                  ></a>
                  <h2 class="font-playfair">Design & Preparation</h2>
                  <p class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream">
                  Each and every gear, barrel, wheel, spring, escapement wheel, pinion, bridge, and hands is storied. Components are restored, polished and meticulously positioned, balanced in precious, crystal clear shatter-resistant Lucite®
                  </p>
                  <a href="#" class="read-more">read more...</a>
                </div>
                <div class="col">
                  <a href="/forming" class="img-wrapper"
                    ><span class="img"
                      ><img
                        src="/images/product/product_07/product_img_00.jpg"
                        alt="Random Image" /></span
                  ></a>
                  <h2 class="font-playfair">Forming & Finishing</h2>
                  <p class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream">
                  Each Berd Vay’e piece is casted with 8-10 layers over a 24 hour period. The sculpture is then baked, carefully protecting it from the formation of air bubbles or imperfections thus resulting in a seamless, crystal clear sculpture.
                  </p>
                  <a href="#" class="read-more">read more...</a>
                </div>
              </div>
            </div>
          </li>
          <!-- Retail -->
          <li>
            <a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">RETAIL</a>
            <div class="mega-menu dark:bg-darkgray z-10 bg-gray-100">
              <div class="content">
                <div class="col">
                  <section>
                    <h2>Featured 1</h2>
                    <a href="#" class="img-wrapper"
                      ><span class="img"
                        ><img
                          src="/assets/homepage/sculptures/knight.png"
                          alt="Random Image" /></span
                    ></a>
                    <p>Lorem ipsum dolor sit amet.</p>
                  </section>
                </div>
                <div class="col">
                  <section>
                    <h2>Featured 2</h2>
                    <ul class="mega-links">
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 1</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 2</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 3</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 4</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 5</a></li>
                    </ul>
                  </section>
                </div>
                <div class="col">
                  <section>
                    <h2>Featured 3</h2>
                    <ul class="mega-links">
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 1</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 2</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 3</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 4</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 5</a></li>
                    </ul>
                  </section>
                </div>
                <div class="col">
                  <section>
                    <h2>Featured 4</h2>
                    <ul class="mega-links">
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 1</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 2</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 3</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 4</a></li>
                      <li class="dark:text-white text-black dark:hover:text-cream hover:text-darkcream"><a href="#">Item 5</a></li>
                    </ul>
                  </section>
                </div>
              </div>
            </div>
          </li>
          
          <li><a href="#" class="menu-item dark:text-white text-black dark:hover:text-cream hover:text-darkcream">CONTACT US</a></li>
        </ul>
    </nav>
    
    <div class="flex gap-4 items-center justify-center md:space-x-4">
        <!-- Search button -->
        <div x-data="{ open: false }" class="relative top-[4px]" id="search-container">
            <div class="absolute left-[-35px] top-[2rem] z-10" x-show="open" @click.away="open = false" x-transition:enter="transform ease-out duration-200" x-transition:enter-start="translate-y-[-100%] opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-[-100%] opacity-0">
                <div class="border flex items-center overflow-hidden rounded-xs md:w-full w-[300px] bg-white border border-darkcream mr-[2px]">
                <input class="w-full p-2 md:w-[20rem] max-w-xs text-gray-700 outline-hidden border-0 focus:outline-hidden focus:ring-0" type="text" wire:ignore wire:model.lazy="search" x-ref="searchInput" placeholder="Search..." >
                    <button class="bg-gray-800 text-white p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <button class="text-cream hover:text-cream" @click="open = !open; if (open) $nextTick(() => $refs.searchInput.focus())">
                <svg class="fill-black dark:fill-white" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.2929 23.7071C22.6834 24.0976 23.3166 24.0976 23.7071 23.7071C24.0976 23.3166 24.0976 22.6834 23.7071 22.2929L22.2929 23.7071ZM18.1176 10.0588C18.1176 14.5096 14.5096 18.1176 10.0588 18.1176V20.1176C15.6142 20.1176 20.1176 15.6142 20.1176 10.0588H18.1176ZM10.0588 18.1176C5.60806 18.1176 2 14.5096 2 10.0588H0C0 15.6142 4.50349 20.1176 10.0588 20.1176V18.1176ZM2 10.0588C2 5.60806 5.60806 2 10.0588 2V0C4.50349 0 0 4.50349 0 10.0588H2ZM10.0588 2C14.5096 2 18.1176 5.60806 18.1176 10.0588H20.1176C20.1176 4.50349 15.6142 0 10.0588 0V2ZM15.8223 17.2365L22.2929 23.7071L23.7071 22.2929L17.2365 15.8223L15.8223 17.2365Z" />
                </svg>
            </button>
        </div>
        <!-- Cart button-->
        <div class="flex justify-end sm:flex space-x-2 relative">
            <button id="cart" class="text-cream hover:text-cream">
                <svg class="fill-black dark:fill-white" width="26" height="26" viewBox="0 0 34 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.0998 23.8952V23.7968H14.88V23.8952L14.8811 23.9439C14.8811 24.4892 14.6636 25.0122 14.2765 25.3978C13.8893 25.7834 13.3643 26 12.8168 26C12.2693 26 11.7443 25.7834 11.3572 25.3978C10.9701 25.0122 10.7526 24.4892 10.7526 23.9439L10.7537 23.8931V23.7968H10.0446C9.74228 23.7968 9.44906 23.6938 9.21361 23.505C8.97816 23.3161 8.81465 23.0527 8.75023 22.7585L8.74914 22.7498L4.60437 3.20239L1.06334 2.61509C0.726095 2.54723 0.428536 2.35146 0.23353 2.06912C0.0385239 1.78678 -0.0386564 1.43999 0.018295 1.10202C0.0752464 0.764047 0.261869 0.461361 0.538735 0.25791C0.815602 0.0544595 1.16103 -0.0338224 1.50203 0.0117159L1.49552 0.0106343L5.92696 0.746111C6.46664 0.838046 6.89013 1.24472 7.00306 1.76604L7.00415 1.7747L7.75557 5.30498L20.6829 6.37792C20.6031 6.8009 20.5623 7.23028 20.5613 7.66067V7.665C20.5613 8.0998 20.5993 8.52594 20.6731 8.94019L20.6666 8.89692C20.3108 9.00861 20.0088 9.24693 19.8184 9.56636C19.6279 9.88579 19.5622 10.2639 19.6339 10.6285L19.6328 10.6188L20.4136 16.2322C20.4614 16.5924 20.6264 16.9082 20.8675 17.1462C21.0868 17.3636 21.3876 17.4998 21.721 17.5031H21.722C21.7959 17.5031 21.8686 17.4955 21.9381 17.4825L21.9305 17.4836C22.3008 17.3872 22.6204 17.1543 22.8246 16.832C23.0289 16.5096 23.1025 16.122 23.0305 15.7477L23.0316 15.7574L22.6059 12.7052C23.2815 13.4053 24.0923 13.9621 24.9895 14.342C25.8868 14.7219 26.8519 14.9172 27.8268 14.9159C27.8735 14.9159 27.9202 14.9159 27.9658 14.9094L28.0668 14.904L27.3403 19.1244C27.3229 19.2267 27.2698 19.3196 27.1903 19.3867C27.1109 19.4537 27.0102 19.4907 26.906 19.491H10.7645L11.1196 21.1642H26.4923C26.8344 21.1778 27.1579 21.3225 27.3953 21.5683C27.6326 21.8141 27.7653 22.1417 27.7656 22.4827C27.7659 22.8237 27.6337 23.1516 27.3968 23.3978C27.1598 23.6439 26.8365 23.7892 26.4945 23.8033H25.2283V23.9017C25.2283 24.447 25.0108 24.97 24.6237 25.3556C24.2366 25.7412 23.7116 25.9578 23.1641 25.9578C22.6166 25.9578 22.0916 25.7412 21.7044 25.3556C21.3173 24.97 21.0998 24.447 21.0998 23.9017V23.8952ZM22.5745 23.8952C22.5747 23.976 22.5917 24.0558 22.6245 24.1296C22.6573 24.2034 22.7052 24.2696 22.765 24.3241C22.8248 24.3785 22.8954 24.42 22.9721 24.4458C23.0489 24.4717 23.1302 24.4814 23.211 24.4744C23.2917 24.4673 23.3701 24.4436 23.4411 24.4048C23.5122 24.366 23.5744 24.3129 23.6238 24.2489C23.6732 24.1849 23.7087 24.1114 23.7281 24.033C23.7474 23.9546 23.7502 23.8731 23.7363 23.7936V23.7968H22.5853C22.5795 23.8293 22.5766 23.8622 22.5766 23.8952H22.5745ZM12.2283 23.8952C12.2285 23.976 12.2456 24.0558 12.2784 24.1296C12.3112 24.2034 12.359 24.2696 12.4188 24.3241C12.4787 24.3785 12.5492 24.42 12.626 24.4458C12.7027 24.4717 12.784 24.4814 12.8648 24.4744C12.9455 24.4673 13.0239 24.4436 13.0949 24.4048C13.166 24.366 13.2282 24.3129 13.2776 24.2489C13.327 24.1849 13.3625 24.1114 13.3819 24.033C13.4012 23.9546 13.4041 23.8731 13.3902 23.7936V23.7968H12.238C12.2326 23.8286 12.2301 23.8608 12.2304 23.8931V23.8952H12.2283ZM16.4273 8.88827C16.057 8.98442 15.7372 9.21732 15.5329 9.53971C15.3286 9.8621 15.2551 10.2498 15.3274 10.6242L15.3263 10.6145L16.107 16.2279C16.1548 16.5881 16.3198 16.9039 16.5609 17.1418C16.7802 17.3592 17.081 17.4955 17.4144 17.4988C17.4882 17.4988 17.5621 17.4912 17.6316 17.4782L17.624 17.4793C17.9942 17.3829 18.3138 17.15 18.5181 16.8276C18.7223 16.5053 18.7959 16.1177 18.724 15.7433L18.725 15.7531L17.9443 10.1397C17.8993 9.79321 17.7395 9.47161 17.4904 9.22573C17.2636 9.00012 16.9574 8.87166 16.6369 8.86772H16.6358C16.5609 8.86772 16.4881 8.87529 16.4176 8.88935L16.4252 8.88827H16.4273ZM12.1208 8.88827C11.7492 8.98398 11.4283 9.21739 11.2236 9.54083C11.019 9.86427 10.946 10.2533 11.0197 10.6285L11.0186 10.6188L11.7994 16.2322C11.8471 16.5924 12.0122 16.9082 12.2533 17.1462C12.4726 17.3636 12.7734 17.4998 13.1067 17.5031H13.1078C13.1817 17.5031 13.2544 17.4955 13.3239 17.4825L13.3163 17.4836C13.6869 17.3877 14.0069 17.1549 14.2114 16.8325C14.4159 16.5101 14.4896 16.1222 14.4174 15.7477L14.4185 15.7574L13.6366 10.1407C13.5916 9.79429 13.4319 9.47269 13.1828 9.22681C12.9563 9.00082 12.6498 8.87228 12.3293 8.8688H12.3282C12.2543 8.8688 12.1827 8.87637 12.1132 8.88935L12.1208 8.88827ZM21.6645 7.66824C21.6648 6.03892 22.3149 4.47643 23.4718 3.32453C24.0446 2.75416 24.7246 2.30176 25.473 1.99316C26.2213 1.68455 27.0234 1.52579 27.8333 1.52593C28.6433 1.52607 29.4453 1.68512 30.1935 1.99398C30.9418 2.30285 31.6216 2.75549 32.1943 3.32606C32.7669 3.89662 33.2211 4.57395 33.5309 5.31935C33.8407 6.06476 34.0001 6.86365 34 7.67041C33.9996 9.30002 33.3492 10.8627 32.192 12.0147C31.0348 13.1667 29.4656 13.8137 27.8295 13.8133C26.1935 13.8128 24.6246 13.1651 23.468 12.0124C22.3114 10.8598 21.6619 9.29678 21.6623 7.66716L21.6645 7.66824ZM23.4312 7.66824C23.4312 8.24391 23.545 8.81395 23.7662 9.3458C23.9874 9.87765 24.3116 10.3609 24.7202 10.768C25.1289 11.175 25.6141 11.4979 26.148 11.7182C26.682 11.9385 27.2543 12.0519 27.8322 12.0519C28.4102 12.0519 28.9825 11.9385 29.5165 11.7182C30.0504 11.4979 30.5356 11.175 30.9443 10.768C31.3529 10.3609 31.6771 9.87765 31.8983 9.3458C32.1194 8.81395 32.2333 8.24391 32.2333 7.66824C32.2334 6.50534 31.7698 5.39001 30.9443 4.56761C30.1189 3.74521 28.9992 3.28311 27.8317 3.28296C26.6642 3.28282 25.5444 3.74465 24.7188 4.56684C23.8931 5.38904 23.4292 6.50426 23.429 7.66716L23.4312 7.66824ZM26.9494 9.42149V8.54757H26.0721C25.8376 8.54757 25.6128 8.45481 25.447 8.28971C25.2813 8.1246 25.1882 7.90066 25.1882 7.66716C25.1882 7.43366 25.2813 7.20973 25.447 7.04462C25.6128 6.87951 25.8376 6.78675 26.0721 6.78675H26.9494V5.91283C26.9494 5.67934 27.0426 5.4554 27.2083 5.29029C27.3741 5.12518 27.5989 5.03243 27.8333 5.03243C28.0678 5.03243 28.2926 5.12518 28.4583 5.29029C28.6241 5.4554 28.7172 5.67934 28.7172 5.91283V6.78675H29.5946C29.829 6.78675 30.0539 6.87951 30.2196 7.04462C30.3854 7.20973 30.4785 7.43366 30.4785 7.66716C30.4785 7.90066 30.3854 8.1246 30.2196 8.28971C30.0539 8.45481 29.829 8.54757 29.5946 8.54757H28.7172V9.42041C28.7172 9.65391 28.6241 9.87784 28.4583 10.043C28.2926 10.2081 28.0678 10.3008 27.8333 10.3008C27.5989 10.3008 27.3741 10.2081 27.2083 10.043C27.0426 9.87784 26.9494 9.65391 26.9494 9.42041V9.42149Z" />
                </svg>
            </button>
        </div>
        <button class="text-cream hover:text-cream">
        <svg class="fill-black dark:fill-white" width="20" height="20" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22L10.26 20.4174C4.08 14.8185 0 11.1139 0 6.59401C0 2.88937 2.904 0 6.6 0C8.688 0 10.692 0.971117 12 2.49373C13.308 0.971117 15.312 0 17.4 0C21.096 0 24 2.88937 24 6.59401C24 11.1139 19.92 14.8185 13.74 20.4174L12 22Z"/>
            </svg>
        </button>

        <button class="bg-cream px-4 py-2 rounded-full text-black text-sm">
            LOGIN/SIGNUP
        </button>
    </div>
</header>
