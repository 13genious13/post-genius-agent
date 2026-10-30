<x-app-layout>

    <div class="space-y-8">

        {{-- Welcome --}}
        <section class="relative overflow-hidden rounded-[28px] border border-indigo-100 bg-gradient-to-br from-white via-indigo-50/70 to-violet-50 p-6 sm:p-8">

            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-200/30 blur-3xl"></div>
            <div class="absolute -bottom-20 right-40 h-52 w-52 rounded-full bg-violet-200/30 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">

                <div>

                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-white/70 px-3 py-1 text-xs font-semibold text-indigo-700">

                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                        AI Workspace Online

                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">

                        Welcome back,
                        {{ auth()->user()->name }}

                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base">
                        Your autonomous marketing workspace for research, strategy,
                        content, creative execution and continuous optimization.
                    </p>

                </div>

                <a
                    href="{{ route('ai.playground') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-300 transition hover:bg-indigo-600"
                >
                    Test AI Intelligence

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 12h14m-6-6l6 6-6 6"
                        />
                    </svg>

                </a>

            </div>

        </section>

        {{-- Stats --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <x-stat-card
                title="Businesses"
                :value="number_format($businesses ?? 0)"
                description="Connected business workspaces"
                icon="business"
            />

            <x-stat-card
                title="Active Campaigns"
                :value="number_format($activeCampaigns ?? 0)"
                description="Campaigns currently active"
                icon="campaign"
            />

            <x-stat-card
                title="AI Actions"
                :value="number_format($aiRequests ?? 0)"
                description="Recorded AI operations"
            />

            <x-stat-card
                title="Tokens Used"
                :value="number_format($tokensUsed ?? 0)"
                description="Total model token consumption"
                icon="token"
            />

        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">

            {{-- AI Team --}}
            <section class="xl:col-span-3 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                            AI Marketing Team
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-slate-900">
                            Specialized AI Agents
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            The intelligence layer being prepared for autonomous marketing workflows.
                        </p>
                    </div>

                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                        Foundation
                    </span>

                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">




                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                        <p class="font-bold">
                            Research Agent
                        </p>




                        <p class="mt-1 text-xs text-slate-400">
                            Live market intelligence
                        </p>




                        <div class="mt-4">
                            <x-status-badge status="ready" />
                        </div>




                    </div>




                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                        <p class="font-bold">
                            Strategy Agent
                        </p>




                        <p class="mt-1 text-xs text-slate-400">
                            Evidence-backed campaign strategy
                        </p>




                        <div class="mt-4">
                            <x-status-badge status="ready" />
                        </div>




                    </div>




                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                        <p class="font-bold">
                            Content Agent
                        </p>




                        <p class="mt-1 text-xs text-slate-400">
                            Planned for next phase
                        </p>




                        <div class="mt-4">
                            <x-status-badge status="planned" />
                        </div>




                    </div>




                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                        <p class="font-bold">
                            Creative Agent
                        </p>




                        <p class="mt-1 text-xs text-slate-400">
                            Not active yet
                        </p>




                        <div class="mt-4">
                            <x-status-badge status="planned" />
                        </div>




                    </div>




                </div>


                @php
                    $agents = [
                        [
                            'name' => 'Research Agent',
                            'description' => 'Market, competitor and audience intelligence',
                        ],
                        [
                            'name' => 'Strategy Agent',
                            'description' => 'Campaign positioning and marketing strategy',
                        ],
                        [
                            'name' => 'Content Agent',
                            'description' => 'Platform-specific content generation',
                        ],
                        [
                            'name' => 'Creative Agent',
                            'description' => 'Creative concepts and visual direction',
                        ],
                        [
                            'name' => 'Analytics Agent',
                            'description' => 'Performance intelligence and optimization',
                        ],
                    ];
                @endphp

                <div class="mt-6 divide-y divide-slate-100">

                    @foreach($agents as $agent)

                        <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-50 to-violet-100 text-indigo-600">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.75 3a2.25 2.25 0 00-2.25 2.25v.879a3 3 0 00-2.121 5.121A3 3 0 007.5 16.371v.879A2.25 2.25 0 009.75 19.5H12V3H9.75zM14.25 3A2.25 2.25 0 0116.5 5.25v.879a3 3 0 012.121 5.121A3 3 0 0116.5 16.371v.879a2.25 2.25 0 01-2.25 2.25H12V3h2.25z"
                                    />
                                </svg>

                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="font-semibold text-slate-800">
                                    {{ $agent['name'] }}
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    {{ $agent['description'] }}
                                </p>

                            </div>

                            <x-status-badge status="ready" />

                        </div>

                    @endforeach

                </div>

            </section>

            {{-- Infrastructure --}}
            <section class="xl:col-span-2 rounded-[28px] border border-slate-200 bg-slate-950 p-6 text-white shadow-sm">

                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-300">
                    AI Infrastructure
                </p>

                <h2 class="mt-3 text-xl font-bold">
                    NVIDIA Nemotron
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Powered through Nebius Token Factory
                </p>

                <div class="mt-7 rounded-2xl border border-white/10 bg-white/5 p-5">

                    <div class="flex items-center justify-between gap-4">

                        <span class="text-sm text-slate-400">
                            Provider
                        </span>

                        <span class="text-sm font-semibold">
                            Nebius
                        </span>

                    </div>

                    <div class="my-4 border-t border-white/10"></div>

                    <div class="flex items-start justify-between gap-4">

                        <span class="text-sm text-slate-400">
                            Model
                        </span>

                        <span class="max-w-[190px] text-right text-xs font-medium leading-5 text-slate-200">
                            {{ config('ai.providers.nebius.model') ?: 'Not configured' }}
                        </span>

                    </div>

                    <div class="my-4 border-t border-white/10"></div>

                    <div class="flex items-center justify-between gap-4">

                        <span class="text-sm text-slate-400">
                            Configuration
                        </span>

                        @if(config('ai.providers.nebius.api_key') && config('ai.providers.nebius.model'))
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-400">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Configured
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-amber-300">
                                <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                                Not Configured
                            </span>
                        @endif

                    </div>

                </div>

                <a
                    href="{{ route('ai.playground') }}"
                    class="mt-6 flex w-full items-center justify-center rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-semibold transition hover:bg-white/15"
                >
                    Open AI Playground
                </a>

                @if(auth()->user()->isAdmin())

                    <a
                        href="{{ route('admin.ai.infrastructure') }}"
                        class="mt-3 flex w-full items-center justify-center rounded-xl px-4 py-2 text-xs font-semibold text-indigo-300 transition hover:text-white"
                    >
                        View Infrastructure →
                    </a>

                @endif

            </section>

        </div>

        {{-- Recent AI activity --}}
        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                        Intelligence
                    </p>

                    <h2 class="mt-1 text-lg font-bold text-slate-900">
                        Recent AI Activity
                    </h2>

                </div>

                <a
                    href="{{ route('ai.playground') }}"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                >
                    Run AI →
                </a>

            </div>

            @if(($recentRequests ?? collect())->isEmpty())

                <div class="px-6 py-14 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>

                    <p class="mt-4 font-semibold text-slate-700">
                        No AI activity yet
                    </p>

                    <p class="mt-1 text-sm text-slate-400">
                        Run your first request from the AI Playground.
                    </p>

                </div>

            @else

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-slate-50/80">

                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-3">Feature</th>
                            <th class="px-6 py-3">Model</th>
                            <th class="px-6 py-3">Tokens</th>
                            <th class="px-6 py-3">Latency</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Time</th>
                        </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-100">

                        @foreach($recentRequests as $request)

                            <tr class="text-sm">

                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-700">
                                    {{ $request->feature ?: 'AI Request' }}
                                </td>

                                <td class="max-w-[250px] truncate px-6 py-4 text-xs text-slate-500">
                                    {{ $request->model ?: '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                    {{ number_format($request->total_tokens ?? 0) }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                    {{ $request->latency_ms !== null
    ? number_format((float) $request->latency_ms, 0) . ' ms'
    : '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <x-status-badge :status="$request->status" />
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-400">
                                    {{ $request->created_at->diffForHumans() }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </section>

    </div>

</x-app-layout>
