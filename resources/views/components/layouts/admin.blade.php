<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/images/favicons/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicons/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicons/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicons/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicons/apple-touch-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon-180x180.png" />
    <title>{{ $title . ' - Admin page' ?? 'Page Title' }}</title>

    <script>
         if (localStorage.getItem('darkMode') === "enabled") {
            document.documentElement.classList.add('dark');
          }
    </script>
    <!-- Bootstrap core CSS -->
    <!--<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">-->

    <!-- <link href="{{ asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="/js/jquery-confirm/jquery-confirm.min.css" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

   @stack('main_header')
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  </head>
  <body class="dark:bg-gray-800 bg-gray-400">

@if( Auth::user())
<nav class="fixed top-0 z-50 w-full bg-gray-700 border-b border-gray-400 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
         </button>
        <a href="https://berdvaye.com" class="flex ms-2 md:me-24">
          <img src="/images/logo-berdvaye-white.png" class="h-8 me-3" alt="BerdVaye Inc." />
        </a>
      </div>
      <div class="flex items-center">
          <div class="flex items-center ms-3">
            <div>
              <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="/assets/person-icon-1680.png" alt="user photo">
              </button>
            </div>
            <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded shadow dark:bg-gray-700 bg-white dark:divide-gray-600" id="dropdown-user">
              <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">
                  {{Auth::user()->name}}
                </p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                {{Auth::user()->email}}
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <!-- <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:text-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a> -->
                  <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:text-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                     onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                     Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     {{ csrf_field() }}
                  </form>
                </li>
                <li>
                     <div class="block px-4 py-2 text-sm text-gray-700 hover:text-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                        <label class="inline-flex items-center cursor-pointer">
                           <input type="checkbox" value="" id="toggleSwitch" class="sr-only peer">
                           <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                           <span class="ms-3 text-sm font-medium dark:text-gray-300">Dark Mode</span>
                        </label>
                     </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-48 h-screen pt-20 transition-transform -translate-x-full dark:bg-none bg-linear-to-l from-gray-700 to-gray-500 border-r border-gray-400 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto dark:bg-gray-800">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="/admin" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li class="border-t border-gray-400 dark:border-gray-700">
            <a href="/admin/products" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 group-hover:text-gray-300 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                     <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
               </svg>
               <span class="ms-3">Products</span>
            </a>
         </li>
         <li>
            <a href="/admin/models" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 group-hover:text-gray-300 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                     <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
               </svg>
               <span class="ms-3">Models</span>
            </a>
         </li>

         <li class="border-t border-gray-400 dark:border-gray-700">
            <a href="/admin/invoices" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="ms-3">Invoices</span>
            </a>
         </li>

         <li>
            <a href="/admin/orders" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="ms-3">Orders</span>
            </a>
         </li>
         <li>
            <a href="/admin/invoice-payments" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="ms-3">Payments</span>
            </a>
         </li>
         <li class="border-t border-gray-400 dark:border-gray-700">
            <a href="/admin/dealers" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="ms-3">Dealers</span>
            </a>
         </li>
         <li>
            <a href="/admin/customers" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="ms-3">Customers</span>
            </a>
         </li>

                  <!-- <li>
                     <a href="/admin/lvreports" class="p-0.5 flex items-center w-full text-gray-300 transition duration-75 rounded-lg pl-11 group hover:text-gray-100 dark:text-white dark:hover:bg-gray-700">Reports</a>
                  </li> -->

         <!-- <li>
            <a href="#" class="p-0.5 flex items-center text-gray-300 rounded-lg dark:text-white hover:text-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-300 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Credentials</span>
            </a>
         </li> -->

      </ul>
   </div>
</aside>

<div class="bg-gray-400 dark:bg-gray-800 p-4 sm:ml-48">
   <div class="bg-gray-200 border-2 border-gray-400 dark:bg-gray-700 dark:border-gray-700 mt-1 p-4 rounded-lg shadow-3xl shadow-gray-700">
        <div class="flex justify-between bg-gray-200 w-full rounded-lg dark:bg-gray-600">
            <h1 class="uppercase tracking-wide text-3xl text-gray-500 dark:text-white p-1.5 items-center">{{$pageName ?? ''}}</h1>
            @yield('button-menu')
        </div>

        {{ $slot }}
   </div>
</div>


@endif
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <!--<script src="{{ asset('/js/bootstrap.min.js') }}"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="/js/customers/jquery.customer.js"></script>
    <script src="/js/general.js"></script>
    <script src="/js/popupMenu.jquery.js"></script>
    <script src="/js/jquery-confirm/jquery-confirm.min.js"></script>
    @stack('footer')
    @stack('jquery')

    <script>

        $(document).ready(function() {

         // darkModeSetting();

         if (localStorage.getItem('darkMode') === "enabled")
            $('#toggleSwitch').prop('checked',true)
         else $('#toggleSwitch').prop('checked',false)
         // function darkModeSetting() {
         //    isDark = localStorage.getItem('darkMode');
         //    if (isDark == 1) {
         //       $('html').addClass('dark');
         //    } else {
         //       $('html').removeClass('dark');
         //       $('#toggleSwitch').prop('checked',false)
         //    }
         // }

         $('#toggleSwitch').change(function() {
               var isDark = 0;

            $('html').toggleClass('dark');
            if ($('html').hasClass('dark')) {
               localStorage.setItem('darkMode', 'enabled');
            } else {
               localStorage.setItem('darkMode', 'disabled');
            }
         })

        function dismiss() {
            el = document.getElementById('alert-border-1');
            el.classList.add( 'opacity-0')
            setTimeout(()=> {
                el.classList.toggle('hidden')
            },500)
        }
      })

   </script>
  </body>
</html>
