<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Inventory System') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 font-sans antialiased">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-indigo-500 selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <div class="flex flex-col items-center">
                            <x-application-mark class="size-20 text-indigo-600" />
                            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900">Inventory System</h1>
                        </div>
                    </div>
                    @if (Route::has('login'))
                        <nav class="-mx-3 flex flex-1 justify-end lg:col-start-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <main class="mt-10">
                    <div class="bg-white p-12 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col items-center text-center">
                        <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                            Streamline Your Warehouse Operations
                        </h2>
                        <p class="mt-6 text-lg leading-8 text-gray-600 max-w-2xl">
                            A powerful, modern inventory management system built for speed and reliability. 
                            Manage warehouses, track locations, and optimize your supply chain with ease.
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            @auth
                                <a href="{{ route('dashboard') }}" class="rounded-full bg-indigo-600 px-8 py-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-full bg-indigo-600 px-8 py-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                                    Get Started
                                </a>
                                <a href="#features" class="text-sm font-semibold leading-6 text-gray-900">
                                    Learn more <span aria-hidden="true">→</span>
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div id="features" class="mt-20 grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3 pb-20">
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5-10.5l-22.5 9.041" />
                                    </svg>
                                </div>
                                Warehouse Control
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Full visibility into multiple warehouse locations, zones, and aisles.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                Precision Tracking
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Track every item down to the bin level with unique barcode generation.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                Activity Audit
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Comprehensive activity logs ensure every inventory movement is recorded.</dd>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Inventory System') }}. All rights reserved.
                </footer>
            </div>
        </div>
    </body>
</html>
