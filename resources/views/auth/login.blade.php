<x-guest-layout>
    <div class="min-h-screen bg-slate-100 text-slate-950">
        <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
            <section class="hidden bg-slate-950 px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-md bg-emerald-500">
                        <span class="text-lg font-bold text-slate-950">IS</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-300">Inventory System</p>
                        <p class="text-sm text-slate-400">Warehouse control panel</p>
                    </div>
                </div>

                <div class="max-w-xl">
                    <p class="mb-4 text-sm font-medium uppercase tracking-wide text-emerald-300">Stock, suppliers,
                        movement</p>
                    <h1 class="text-4xl font-semibold leading-tight">Sign in to manage inventory with clearer stock
                        visibility.</h1>
                    <div class="mt-10 grid grid-cols-3 gap-4">
                        <div class="rounded-md border border-white/10 bg-white/5 p-4">
                            <p class="text-2xl font-semibold">24</p>
                            <p class="mt-1 text-sm text-slate-400">Low stock items</p>
                        </div>
                        <div class="rounded-md border border-white/10 bg-white/5 p-4">
                            <p class="text-2xl font-semibold">8</p>
                            <p class="mt-1 text-sm text-slate-400">Pending orders</p>
                        </div>
                        <div class="rounded-md border border-white/10 bg-white/5 p-4">
                            <p class="text-2xl font-semibold">126</p>
                            <p class="mt-1 text-sm text-slate-400">Active SKUs</p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-slate-500">Secure access for inventory staff and administrators.</p>
            </section>

            <main class="flex items-center justify-center px-6 py-10 sm:px-10">
                <div class="w-full max-w-md">
                    <div class="mb-8 lg:hidden">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-md bg-emerald-500">
                            <span class="text-lg font-bold text-slate-950">IS</span>
                        </div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Inventory System</p>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold">Welcome back Master Stockers!</h2>
                            <p class="mt-2 text-sm text-slate-600">Use your inventory account to continue.</p>
                        </div>

                        <x-validation-errors class="mb-5" />

                        @session('status')
                            <div
                                class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                {{ $value }}
                            </div>
                        @endsession

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div>
                                <x-label for="email" value="{{ __('Email address') }}" />
                                <x-input id="email"
                                    class="mt-2 block w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                                    type="email" name="email" :value="old('email')" required autofocus
                                    autocomplete="username" />
                            </div>

                            <div>
                                <x-label for="password" value="{{ __('Password') }}" />
                                <x-input id="password"
                                    class="mt-2 block w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                                    type="password" name="password" required autocomplete="current-password" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label for="remember_me" class="flex items-center">
                                    <x-checkbox id="remember_me" name="remember"
                                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                    <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm font-medium text-emerald-700 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                {{ __('Log in') }}
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-guest-layout>