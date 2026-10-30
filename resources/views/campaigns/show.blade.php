<x-app-layout>




    <div class="space-y-6">




        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">




            <div>




                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    {{ str($campaign->campaign_type)->replace('_', ' ')->title() }}
                </p>




                <h1 class="mt-2 text-3xl font-bold">
                    {{ $campaign->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    {{ $campaign->business->name }}
                </p>




            </div>




            <div class="flex flex-wrap gap-3">




                <a
                    href="{{ route('campaigns.edit', $campaign) }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
                >
                    Edit Campaign
                </a>




                <a
                    href="{{ route('campaigns.brief.show', $campaign) }}"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white"
                >
                    AI Campaign Brief
                </a>




            </div>




        </div>




        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">




            <section class="xl:col-span-2 space-y-6">




                <div class="rounded-[28px] border border-slate-200 bg-white p-6">




                    <div class="flex items-center justify-between">




                        <h2 class="text-lg font-bold">
                            Campaign Objective
                        </h2>




                        <x-status-badge :status="$campaign->status" />




                    </div>




                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">
                        {{ $campaign->objective }}
                    </p>




                    @if($campaign->offer)




                        <div class="mt-6 rounded-2xl bg-indigo-50 p-5">




                            <p class="text-xs font-bold uppercase text-indigo-600">
                                Offer
                            </p>




                            <p class="mt-2 text-sm leading-6 text-slate-700">
                                {{ $campaign->offer }}
                            </p>




                        </div>




                    @endif




                </div>




                <div class="rounded-[28px] border border-slate-200 bg-white p-6">




                    <h2 class="text-lg font-bold">
                        Platforms & Formats
                    </h2>




                    <div class="mt-5">




                        <p class="text-xs font-bold uppercase text-slate-400">
                            Platforms
                        </p>




                        <div class="mt-3 flex flex-wrap gap-2">




                            @forelse($campaign->platforms ?? [] as $platform)




                                <span class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-600">
{{ ucfirst($platform) }}
</span>




                            @empty
                                <span class="text-sm text-slate-400">None selected</span>
                            @endforelse




                        </div>




                    </div>




                    <div class="mt-6">




                        <p class="text-xs font-bold uppercase text-slate-400">
                            Formats
                        </p>




                        <div class="mt-3 flex flex-wrap gap-2">




                            @forelse($campaign->content_formats ?? [] as $format)




                                <span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
{{ str($format)->replace('_', ' ')->title() }}
</span>




                            @empty
                                <span class="text-sm text-slate-400">None selected</span>
                            @endforelse




                        </div>




                    </div>




                </div>




            </section>




            <aside class="space-y-6">




                <div class="rounded-[28px] bg-slate-950 p-6 text-white">




                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                        Campaign Context
                    </p>




                    <div class="mt-5 space-y-5">




                        <div>
                            <p class="text-xs text-slate-500">
                                Marketing Goal
                            </p>




                            <p class="mt-1 text-sm font-semibold">
                                {{ $campaign->marketingGoal?->name ?? 'Not Linked' }}
                            </p>
                        </div>




                        <div>
                            <p class="text-xs text-slate-500">
                                Budget
                            </p>




                            <p class="mt-1 font-semibold">
                                {{ $campaign->budget !== null
    ? ($campaign->currency ?? 'INR') . ' ' . number_format((float) $campaign->budget, 2)
    : 'Not Set' }}
                            </p>
                        </div>




                        <div>
                            <p class="text-xs text-slate-500">
                                Timeline
                            </p>




                            <p class="mt-1 text-sm font-semibold">
                                {{ $campaign->start_date?->format('d M Y') ?? '—' }}
                                →
                                {{ $campaign->end_date?->format('d M Y') ?? '—' }}
                            </p>
                        </div>




                    </div>




                </div>


                <a
                    href="{{ route('strategies.campaign', $campaign) }}"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Strategy Agent
                </a>
                <a
                    href="{{ route('agent-runs.create', $campaign) }}"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Run Agent
                </a>

                <div class="rounded-[28px] border border-indigo-100 bg-indigo-50 p-6">




                    <p class="text-xs font-bold uppercase text-indigo-600">
                        Campaign Strategy
                    </p>




                    @if($campaign->latestStrategy)




                        <div class="mt-3 flex items-center justify-between gap-4">




                            <div>
                                <p class="font-bold">
                                    Strategy v{{ $campaign->latestStrategy->version }}
                                </p>




                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $campaign->latestStrategy->strategic_thesis ?: 'Strategy generation in progress.' }}
                                </p>
                            </div>




                            <x-status-badge :status="$campaign->latestStrategy->status" />




                        </div>




                    @else




                        <p class="mt-3 font-bold">
                            No Strategy Generated
                        </p>




                        <p class="mt-2 text-sm text-slate-500">
                            Complete research and let the Strategy Agent create the execution plan.
                        </p>




                    @endif




                </div>


                <div class="rounded-[28px] border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-6">




                    <p class="text-xs font-bold uppercase text-indigo-600">
                        Strategy Intelligence
                    </p>




                    @if($campaign->business->latestIntelligence)




                        <p class="mt-3 font-bold text-slate-900">
                            Business Intelligence Connected
                        </p>




                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            The latest business intelligence will be supplied to the Campaign Strategy Engine.
                        </p>




                    @else




                        <p class="mt-3 font-bold text-amber-800">
                            Business Intelligence Missing
                        </p>




                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            The campaign brief can still run, but completing Phase 2 intelligence first will improve context.
                        </p>




                    @endif




                </div>




            </aside>




        </div>




    </div>




</x-app-layout>
