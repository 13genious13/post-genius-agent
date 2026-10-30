<x-app-layout>




    @php
        $run = $researchRun;




        $sections = [
        'Market Insights' => $run->market_insights ?? [],
        'Audience Insights' => $run->audience_insights ?? [],
        'Competitor Insights' => $run->competitor_insights ?? [],
        'Trend Insights' => $run->trend_insights ?? [],
        ];
    @endphp




    <div
        class="space-y-7"
        @if($run->isRunning())
        x-data
        x-init="setTimeout(() => window.location.reload(), 5000)"
        @endif
    >




        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">




            <div>




                <div class="flex flex-wrap items-center gap-3">




                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                        Research Agent
                    </p>




                    <x-status-badge :status="$run->status" />




                </div>




                <h1 class="mt-2 text-3xl font-bold">
                    {{ $run->campaign->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    Research Run v{{ $run->version }}
                </p>




            </div>




            <div class="flex flex-wrap gap-3">




                <a
                    href="{{ route('research.sources', $run) }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
                >
                    View Sources
                </a>




                <a
                    href="{{ route('research.campaign', $run->campaign) }}"
                    class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white"
                >
                    Research History
                </a>




            </div>




        </div>




        @if($run->isRunning())




            <div class="rounded-[28px] border border-indigo-100 bg-indigo-50 p-6">




                <div class="flex items-center gap-4">




                    <div class="h-3 w-3 animate-pulse rounded-full bg-indigo-600"></div>




                    <div>




                        <h2 class="font-bold text-indigo-900">
                            Research Agent is working
                        </h2>




                        <p class="mt-1 text-sm text-indigo-700">
                            Current stage:
                            {{ ucfirst($run->status) }}.
                            This page refreshes automatically.
                        </p>




                    </div>




                </div>




            </div>




        @endif




        @if($run->status === 'failed')




            <div class="rounded-[28px] border border-red-200 bg-red-50 p-6">




                <h2 class="font-bold text-red-800">
                    Research Failed
                </h2>




                <p class="mt-2 text-sm text-red-700">
                    {{ $run->error_message }}
                </p>




            </div>




        @endif




        <section class="rounded-[28px] bg-slate-950 p-6 text-white sm:p-8">




            <div class="grid gap-6 lg:grid-cols-4">




                <div class="lg:col-span-2">




                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                        Research Objective
                    </p>




                    <p class="mt-3 text-sm leading-7 text-slate-300">
                        {{ $run->objective }}
                    </p>




                </div>




                <div>
                    <p class="text-xs text-slate-500">
                        Queries
                    </p>




                    <p class="mt-2 text-3xl font-bold">
                        {{ $run->queries->count() }}
                    </p>
                </div>




                <div>
                    <p class="text-xs text-slate-500">
                        Sources
                    </p>




                    <p class="mt-2 text-3xl font-bold">
                        {{ $run->sources->count() }}
                    </p>
                </div>




            </div>




        </section>




        @if($run->research_plan)




            <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                <h2 class="text-lg font-bold">
                    Agent Research Plan
                </h2>




                @if(data_get($run->research_plan, 'reasoning_summary'))




                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ data_get($run->research_plan, 'reasoning_summary') }}
                    </p>




                @endif




                <div class="mt-5 space-y-3">




                    @foreach($run->queries as $query)




                        <div class="flex flex-col gap-3 rounded-2xl bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">




                            <div>




                                <p class="text-xs font-bold uppercase text-indigo-600">
                                    {{ $query->category }}
                                </p>




                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    {{ $query->query }}
                                </p>




                                @if($query->purpose)




                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $query->purpose }}
                                    </p>




                                @endif




                            </div>




                            <div class="flex items-center gap-3">




                                <x-status-badge :status="$query->status" />




                                <span class="text-xs text-slate-400">
{{ $query->result_count }} results
</span>




                            </div>




                        </div>




                    @endforeach




                </div>




            </section>




        @endif




        @if($run->status === 'completed')




            <section class="rounded-[28px] border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-6 sm:p-8">




                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                    Research Summary
                </p>




                <p class="mt-4 text-base leading-8 text-slate-700">
                    {{ $run->executive_summary }}
                </p>




            </section>




            @foreach($sections as $title => $items)




                @if(!empty($items))




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <h2 class="text-lg font-bold">
                            {{ $title }}
                        </h2>




                        <div class="mt-5 space-y-4">




                            @foreach($items as $item)




                                <div class="rounded-2xl bg-slate-50 p-5">




                                    <p class="text-sm leading-7 text-slate-700">
                                        {{ $item['insight']
                                        ?? $item['trend']
                                        ?? '' }}
                                    </p>




                                    @if(isset($item['why_it_matters']))




                                        <p class="mt-2 text-sm text-slate-500">
                                            {{ $item['why_it_matters'] }}
                                        </p>




                                    @endif




                                    @if(isset($item['competitor']))




                                        <p class="mb-2 text-xs font-bold uppercase text-indigo-600">
                                            {{ $item['competitor'] }}
                                        </p>




                                    @endif




                                    <div class="mt-3 flex flex-wrap gap-2">




                                        @foreach($item['source_ids'] ?? [] as $sourceId)




                                            <a
                                                href="{{ route('research.sources', $run) }}#source-{{ $sourceId }}"
                                                class="rounded-lg bg-white px-2.5 py-1 text-xs font-semibold text-indigo-600"
                                            >
                                                Source {{ $sourceId }}
                                            </a>




                                        @endforeach




                                        @if(isset($item['confidence']))




                                            <span class="rounded-lg bg-white px-2.5 py-1 text-xs text-slate-500">
{{ ucfirst($item['confidence']) }} confidence
</span>




                                        @endif




                                    </div>




                                </div>




                            @endforeach




                        </div>




                    </section>




                @endif




            @endforeach




            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">




                <section class="rounded-[28px] border border-emerald-100 bg-emerald-50 p-6">




                    <h2 class="text-lg font-bold text-emerald-900">
                        Opportunities
                    </h2>




                    <div class="mt-4 space-y-4">




                        @foreach($run->opportunities ?? [] as $item)




                            <div class="rounded-2xl bg-white/70 p-4">




                                <p class="font-semibold text-emerald-900">
                                    {{ $item['opportunity'] ?? '' }}
                                </p>




                                <p class="mt-2 text-sm leading-6 text-emerald-800">
                                    {{ $item['evidence'] ?? '' }}
                                </p>




                            </div>




                        @endforeach




                    </div>




                </section>




                <section class="rounded-[28px] border border-amber-100 bg-amber-50 p-6">




                    <h2 class="text-lg font-bold text-amber-900">
                        Risks / Gaps
                    </h2>




                    <div class="mt-4 space-y-3">




                        @foreach($run->risks ?? [] as $item)




                            <p class="text-sm leading-6 text-amber-800">
                                • {{ $item['risk'] ?? '' }}
                            </p>




                        @endforeach




                        @foreach($run->unknowns ?? [] as $unknown)




                            <p class="text-sm leading-6 text-amber-800">
                                • Unknown: {{ $unknown }}
                            </p>




                        @endforeach




                    </div>




                </section>




            </div>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                <h2 class="text-lg font-bold">
                    Research Recommendations
                </h2>




                <div class="mt-5 space-y-4">




                    @foreach($run->recommendations ?? [] as $item)




                        <div class="rounded-2xl border border-slate-100 p-5">




                            <p class="font-bold">
                                {{ $item['recommendation'] ?? '' }}
                            </p>




                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                {{ $item['reason'] ?? '' }}
                            </p>




                        </div>




                    @endforeach




                </div>




            </section>




        @endif




    </div>




</x-app-layout>
