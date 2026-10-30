<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $title ?? 'PostGenius Agent' }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    class="bg-slate-50 text-slate-900 antialiased"
    x-data="{ sidebarOpen: false }"
>

<div class="min-h-screen">

    {{-- Desktop Sidebar --}}
    <div class="hidden lg:block">
        <x-app-sidebar />
    </div>

    {{-- Mobile Sidebar --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        class="fixed inset-0 z-50 lg:hidden"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm"
            @click="sidebarOpen = false"
        ></div>

        <div class="relative h-full w-[290px] max-w-[85vw]">
            <x-app-sidebar mobile="true" />

            <button
                type="button"
                @click="sidebarOpen = false"
                class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white hover:bg-white/20"
            >
                <span class="sr-only">Close sidebar</span>

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
    </div>

    <div class="lg:pl-[280px]">

        <x-app-header />

        <main class="min-h-[calc(100vh-73px)]">

            <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

                {{-- Flash success --}}
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Flash error --}}
                @if(session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot ?? '' }}

                @yield('content')

            </div>

        </main>

    </div>

</div>

</body>
</html>
