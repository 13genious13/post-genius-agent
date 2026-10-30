<x-app-layout>




    @php
        $brief = $campaign->latestBrief;
    @endphp




    <div class="space-y-6">




        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">




            <div>




                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    AI Campaign Strategy
                </p>




                <h1 class="mt-2 text-3xl font-bold">
                    {{ $campaign->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    AI-generated strategic brief based on business and campaign context.
                </p>




            </div>




            <form
                method="POST"
                action="{{ route('campaigns.brief.generate', $campaign) }}"
                x-data="{ loading: false }"
                @submit="loading = true"
            >
                @csrf




                <button
                    type="submit"
                    :disabled="loading"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60"
                >
<span x-show="!loading">
{{ $brief ? 'Regenerate Brief' : 'Generate AI Brief' }}
</span>




                    <span x-cloak x-show="loading">
Building Strategy...
</span>
                </button>




            </form>




        </div>




        <a
            href="{{ route('campaigns.show', $campaign) }}"
            class="inline-flex text-sm font-semibold text-slate-500 hover:text-indigo-600"
        >
            ← Campaign Overview
        </a>




        @if(!$brief)




            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">




                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">




                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>




                </div>




                <h2 class="mt-5 text-xl font-bold">
                    Campaign Strategy Not Generated
                </h2>




                <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500">
                    Generate the brief to convert the campaign configuration and business intelligence into an actionable strategy.
                </p>




            </div>




        @else




            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">




                <div class="space-y-6 xl:col-span-2">




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <div class="flex items-center justify-between">




                            <h2 class="text-lg font-bold">
                                Executive Summary
                            </h2>




                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600">
v{{ $brief->version }}
</span>




                        </div>




                        <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">
                            {{ $brief->executive_summary }}
                        </p>




                    </section>




                    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">




                        <div class="rounded-[28px] border border-slate-200 bg-white p-6">




                            <h2 class="font-bold">
                                Strategic Direction
                            </h2>




                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                {{ $brief->strategic_direction }}
                            </p>




                        </div>




                        <div class="rounded-[28px] border border-indigo-100 bg-indigo-50 p-6">




                            <h2 class="font-bold text-indigo-900">
                                Core Message
                            </h2>




                            <p class="mt-3 text-sm leading-7 text-indigo-800">
                                {{ $brief->core_message }}
                            </p>




                        </div>




                    </section>




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <h2 class="text-lg font-bold">
                            Audience Strategy
                        </h2>




                        <div class="mt-5 space-y-4">




                            @foreach($brief->audience_strategy ?? [] as $item)




                                <div class="rounded-2xl bg-slate-50 p-5">




                                    <h3 class="font-bold">
                                        {{ $item['audience'] ?? 'Audience' }}
                                    </h3>




                                    <div class="mt-3 grid gap-3 text-sm md:grid-cols-3">




                                        <div>
                                            <p class="text-xs font-bold uppercase text-slate-400">
                                                Need
                                            </p>




                                            <p class="mt-1 text-slate-600">
                                                {{ $item['need'] ?? '' }}
                                            </p>
                                        </div>




                                        <div>
                                            <p class="text-xs font-bold uppercase text-slate-400">
                                                Message
                                            </p>




                                            <p class="mt-1 text-slate-600">
                                                {{ $item['message'] ?? '' }}
                                            </p>
                                        </div>




                                        <div>
                                            <p class="text-xs font-bold uppercase text-slate-400">
                                                Action
                                            </p>




                                            <p class="mt-1 text-slate-600">
                                                {{ $item['action'] ?? '' }}
                                            </p>
                                        </div>




                                    </div>




                                </div>




                            @endforeach




                        </div>




                    </section>




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <h2 class="text-lg font-bold">
                            Content Themes
                        </h2>




                        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">




                            @foreach($brief->content_themes ?? [] as $theme)




                                <div class="rounded-2xl border border-slate-100 p-5">




                                    <h3 class="font-bold">
                                        {{ $theme['theme'] ?? '' }}
                                    </h3>




                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        {{ $theme['purpose'] ?? '' }}
                                    </p>




                                    <div class="mt-4 flex flex-wrap gap-2">




                                        @foreach($theme['example_topics'] ?? [] as $topic)




                                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs text-slate-600">
{{ $topic }}
</span>




                                        @endforeach




                                    </div>




                                </div>




                            @endforeach




                        </div>




                    </section>




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <h2 class="text-lg font-bold">
                            Platform Strategy
                        </h2>




                        <div class="mt-5 space-y-4">




                            @foreach($brief->platform_strategy ?? [] as $platform)




                                <div class="grid gap-4 rounded-2xl bg-slate-50 p-5 md:grid-cols-4">




                                    <div>
                                        <p class="font-bold">
                                            {{ ucfirst($platform['platform'] ?? '') }}
                                        </p>
                                    </div>




                                    <div class="md:col-span-2">
                                        <p class="text-xs font-bold uppercase text-slate-400">
                                            Approach
                                        </p>




                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $platform['content_approach'] ?? '' }}
                                        </p>
                                    </div>




                                    <div>
                                        <p class="text-xs font-bold uppercase text-slate-400">
                                            Priority
                                        </p>




                                        <p class="mt-1 text-sm font-semibold">
                                            {{ ucfirst($platform['priority'] ?? '') }}
                                        </p>
                                    </div>




                                </div>




                            @endforeach




                        </div>




                    </section>




                </div>




                <aside class="space-y-6">




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <h2 class="font-bold">
                            Execution Priorities
                        </h2>




                        <ol class="mt-4 space-y-3">




                            @foreach($brief->execution_priorities ?? [] as $priority)




                                <li class="flex gap-3 text-sm leading-6 text-slate-600">




<span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600">
{{ $loop->iteration }}
</span>




                                    {{ $priority }}




                                </li>




                            @endforeach




                        </ol>




                    </section>




                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                        <h2 class="font-bold">
                            KPI Recommendations
                        </h2>




                        <div class="mt-4 space-y-4">




                            @foreach($brief->kpi_recommendations ?? [] as $kpi)




                                <div>




                                    <p class="text-sm font-semibold">
                                        {{ $kpi['kpi'] ?? '' }}
                                    </p>




                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        {{ $kpi['reason'] ?? '' }}
                                    </p>




                                </div>




                            @endforeach




                        </div>




                    </section>




                    @if(!empty($brief->risks))




                        <section class="rounded-[28px] border border-amber-200 bg-amber-50 p-6">




                            <h2 class="font-bold text-amber-900">
                                Risks
                            </h2>




                            <ul class="mt-4 space-y-3">




                                @foreach($brief->risks as $risk)




                                    <li class="text-sm leading-6 text-amber-800">
                                        • {{ $risk }}
                                    </li>




                                @endforeach




                            </ul>




                        </section>




                    @endif




                    <section class="rounded-[28px] bg-slate-950 p-6 text-white">




                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                            Strategy Engine
                        </p>




                        <p class="mt-5 text-xs text-slate-500">
                            Provider
                        </p>




                        <p class="mt-1 font-semibold">
                            {{ $brief->provider }}
                        </p>




                        <p class="mt-4 text-xs text-slate-500">
                            Model
                        </p>




                        <p class="mt-1 break-all text-xs leading-5">
                            {{ $brief->model }}
                        </p>




                        <p class="mt-4 text-xs text-slate-500">
                            Generated
                        </p>




                        <p class="mt-1 text-sm font-semibold">
                            {{ $brief->generated_at?->format('d M Y, h:i A') }}
                        </p>




                    </section>




                </aside>




            </div>




        @endif




    </div>




</x-app-layout>
