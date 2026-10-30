
@props([
'mobile' => false,
])

@php
    $navItem = 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition';

    $activeItem = 'bg-white/10 text-white shadow-sm';

    $inactiveItem = 'text-slate-300 hover:bg-white/5 hover:text-white';

    $sectionTitle = 'px-3 pb-2 pt-6 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500';
@endphp

<aside
    class="{{ $mobile ? 'relative' : 'fixed inset-y-0 left-0 z-40' }} flex h-screen w-[280px] flex-col border-r border-slate-800 bg-slate-950"
>

    {{-- Logo --}}
    <div class="flex h-[73px] shrink-0 items-center border-b border-slate-800 px-6">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-lg shadow-indigo-950/40">

                <svg
                    class="h-5 w-5 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"
                    />
                </svg>

            </div>

            <div>
                <div class="text-[15px] font-bold tracking-tight text-white">
                    PostGenius
                </div>

                <div class="text-[11px] font-medium text-indigo-300">
                    AGENT
                </div>
            </div>

        </a>

    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-4 pb-8">

        <div class="{{ $sectionTitle }}">
            Overview
        </div>

        <a
            href="{{ route('dashboard') }}"
            class="{{ $navItem }} {{ request()->routeIs('dashboard') ? $activeItem : $inactiveItem }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
            </svg>

            Dashboard
        </a>

        <div class="{{ $sectionTitle }}">
            Workspace
        </div>

        <a
            href="{{ route('businesses.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('businesses.*') ? $activeItem : $inactiveItem }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 21h18M6 21V5a2 2 0 012-2h8a2 2 0 012 2v16M9 8h2m2 0h2M9 12h2m2 0h2"/>
            </svg>

            Businesses
        </a>

        <a
            href="{{ route('marketing-goals.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('marketing-goals.*') ? $activeItem : $inactiveItem }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <circle cx="12" cy="12" r="5" stroke-width="2"/>
                <circle cx="12" cy="12" r="1" stroke-width="2"/>
            </svg>
            Marketing Goals
        </a>

        <a
            href="{{ route('campaigns.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('campaigns.*') ? $activeItem : $inactiveItem }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5l8-3v16l-8-3M5 9h6v6H5a2 2 0 01-2-2v-2a2 2 0 012-2z"
                />
            </svg>
            Campaigns
        </a>

        <div class="{{ $sectionTitle }}">
            AI Team
        </div>

        @foreach([
            'content-agent' => 'Content Agent',
        ] as $slug => $label)

            <a
                href="{{ $slug === 'content-agent' ? route('content-agent.index') : route('coming-soon', ['module' => $slug]) }}"
                class="{{ $navItem }} {{ $inactiveItem }}"
            >
                <span class="flex h-5 w-5 items-center justify-center">
                    <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                </span>

                {{ $label }}
            </a>

        @endforeach

        <a href="{{ route('creative-agent.index') }}" class="{{ $navItem }} {{ request()->routeIs('creative-agent.*') ? $activeItem : $inactiveItem }}"><span class="flex h-5 w-5 items-center justify-center">
                    <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                </span> Creative Agent</a>

        <a
            href="{{ route('research.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('research.*') ? $activeItem : $inactiveItem }}"
        >
            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <circle
                    cx="11"
                    cy="11"
                    r="7"
                    stroke-width="2"
                />




                <path
                    stroke-linecap="round"
                    stroke-width="2"
                    d="M20 20l-4-4"
                />
            </svg>




            Research Agent
        </a>

        <a
            href="{{ route('strategies.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('strategies.*') ? $activeItem : $inactiveItem }}"
        >
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
                    d="M9 18h6M10 22h4M8 2h8a4 4 0 014 4c0 2-1 3.5-2.5 4.8C16 12 15 13 15 15H9c0-2-1-3-2.5-4.2C5 9.5 4 8 4 6a4 4 0 014-4z"
                />
            </svg>




            Strategy Agent
        </a>

        <a
            href="{{ route('agent-center.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('agent-center.*', 'agent-runs.*') ? $activeItem : $inactiveItem }}"
        >
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
                    d="M12 3v3m0 12v3M3 12h3m12 0h3M6.3 6.3l2.1 2.1m7.2 7.2 2.1 2.1m0-11.4-2.1 2.1m-7.2 7.2-2.1 2.1M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                />
            </svg>
            Agent Center
        </a>

        <a
            href="{{ route('content-library.index') }}"
            class="{{ $navItem }} {{ request()->routeIs('content-library.*', 'content-library.*') ? $activeItem : $inactiveItem }}"
        >
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
                    d="M12 3v3m0 12v3M3 12h3m12 0h3M6.3 6.3l2.1 2.1m7.2 7.2 2.1 2.1m0-11.4-2.1 2.1m-7.2 7.2-2.1 2.1M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                />
            </svg>
            Content Library
        </a>


        <div class="{{ $sectionTitle }}">
            Automation
        </div>

        @foreach([
  ['label'=>'Activity', 'url'=>Route::has('activity.index') ? route('activity.index') : route('coming-soon',['module'=>'activity'])],
  ['label'=>'Approvals', 'url'=>route('workflow.approvals')],
  ['label'=>'Schedule', 'url'=>route('workflow.schedule')],
] as $item)
            <a href="{{ $item['url'] }}" class="{{ $navItem }} {{ $inactiveItem }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6l4 2M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
{{--        @foreach([--}}
{{--            'activity' => 'Activity',--}}
{{--            'approvals' => 'Approvals',--}}
{{--            'schedule' => 'Schedule',--}}
{{--        ] as $slug => $label)--}}

{{--            <a--}}
{{--                href="{{ $slug === 'activity' ? route('activity.index') : route('coming-soon', ['module' => $slug]) }}"--}}
{{--                class="{{ $navItem }} {{ $inactiveItem }}"--}}
{{--            >--}}
{{--                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                          d="M12 6v6l4 2M12 22a10 10 0 110-20 10 10 0 010 20z"/>--}}
{{--                </svg>--}}

{{--                {{ $label }}--}}
{{--            </a>--}}

{{--        @endforeach--}}

        <div class="{{ $sectionTitle }}">
            Insights
        </div>

        <a
            href="{{ route('performance.index') }}"
            class="{{ $navItem }} {{ $inactiveItem }}"
        >
            Performance
        </a>

        <a
            href="{{ route('ai-usage.index') }}"
            class="{{ $navItem }} {{ $inactiveItem }}"
        >
            AI Usage
        </a>

        <div class="{{ $sectionTitle }}">
            Developer
        </div>

        <a
            href="{{ route('ai.playground') }}"
            class="{{ $navItem }} {{ request()->routeIs('ai.playground*') ? $activeItem : $inactiveItem }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 9l-3 3 3 3m8-6l3 3-3 3m-5 4l2-14"/>
            </svg>

            AI Playground
        </a>

        @if(auth()->user()?->isAdmin())

            <div class="{{ $sectionTitle }}">
                Admin
            </div>

            <a
                href="{{ route('admin.ai.infrastructure') }}"
                class="{{ $navItem }} {{ request()->routeIs('admin.ai.infrastructure*') ? $activeItem : $inactiveItem }}"
            >
                AI Infrastructure
            </a>

            <a
                href="{{ route('admin.users.index') }}"
                class="{{ $navItem }} {{ $inactiveItem }}"
            >
                Users
            </a>

            <a
                href="{{ route('admin.system-settings.index') }}"
                class="{{ $navItem }} {{ $inactiveItem }}"
            >
                System Settings
            </a>

        @endif

        <div class="{{ $sectionTitle }}">
            Settings
        </div>

        @if(Route::has('profile.edit'))
            <a
                href="{{ route('profile.edit') }}"
                class="{{ $navItem }} {{ request()->routeIs('profile.*') ? $activeItem : $inactiveItem }}"
            >
                Profile
            </a>
        @endif

    </nav>

    {{-- User --}}
    <div class="shrink-0 border-t border-slate-800 p-4">

        <div class="flex items-center gap-3 rounded-2xl bg-slate-900 px-3 py-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 text-sm font-bold text-indigo-300">
                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">

                <div class="truncate text-sm font-semibold text-white">
                    {{ auth()->user()?->name }}
                </div>

                <div class="truncate text-xs text-slate-500">
                    {{ auth()->user()?->email }}
                </div>

            </div>

        </div>

    </div>

</aside>
