<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    AI Business Intelligence
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-950">
                    {{ $business->name }}
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Structured intelligence used by future marketing agents.
                </p>

            </div>

            <form
                method="POST"
                action="{{ route('businesses.intelligence.generate', $business) }}"
                x-data="{ loading: false }"
                @submit="loading = true"
            >
                @csrf

                <button
                    type="submit"
                    :disabled="loading"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60"
                >
 <span x-show="!loading">
 {{ $business->latestIntelligence ? 'Regenerate Intelligence' : 'Generate Intelligence' }}
 </span>

                    <span
                        x-cloak
                        x-show="loading"
                    >
 Analyzing Business...
 </span>
                </button>

            </form>

        </div>

        <x-business-tabs :business="$business" />

        @if($business->profile_completion < 50)

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <p class="font-semibold text-amber-800">
                    Business profile needs more information.
                </p>

                <p class="mt-1 text-sm text-amber-700">
                    Current completion: {{ $business->profile_completion }}%.
                    Complete at least 50% before generating intelligence.
                </p>

            </div>

        @endif

        @php
            $intel = $business->latestIntelligence;
        @endphp

        @if(!$intel)

            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>

                <h2 class="mt-5 text-xl font-bold">
                    Intelligence not generated yet
                </h2>

                <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-500">
                    Once your business profile contains enough information,
                    NVIDIA Nemotron will convert it into structured marketing intelligence.
                </p>

            </div>

        @else

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

                <div class="space-y-6 xl:col-span-2">

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">

                        <div class="flex items-center justify-between">

                            <h2 class="text-lg font-bold">
                                Business Summary
                            </h2>

                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600">
 v{{ $intel->version }}
 </span>

                        </div>

                        <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">
                            {{ $intel->business_summary }}
                        </p>

                    </section>

                    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div class="rounded-[28px] border border-slate-200 bg-white p-6">

                            <h2 class="font-bold">
                                Market Position
                            </h2>

                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                {{ $intel->market_position }}
                            </p>

                        </div>

                        <div class="rounded-[28px] border border-slate-200 bg-white p-6">

                            <h2 class="font-bold">
                                Value Proposition
                            </h2>

                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                {{ $intel->value_proposition }}
                            </p>

                        </div>

                    </section>

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">

                        <h2 class="text-lg font-bold">
                            Content Pillars
                        </h2>

                        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">

                            @foreach($intel->content_pillars ?? [] as $pillar)

                                <div class="rounded-2xl bg-slate-50 p-5">

                                    <h3 class="font-bold text-slate-800">
                                        {{ $pillar['name'] ?? 'Content Pillar' }}
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        {{ $pillar['purpose'] ?? '' }}
                                    </p>

                                    @if(!empty($pillar['example_topics']))

                                        <div class="mt-4 flex flex-wrap gap-2">

                                            @foreach($pillar['example_topics'] as $topic)

                                                <span class="rounded-lg bg-white px-2.5 py-1 text-xs text-slate-600 shadow-sm">
 {{ $topic }}
 </span>

                                            @endforeach

                                        </div>

                                    @endif

                                </div>

                            @endforeach

                        </div>

                    </section>

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">

                        <h2 class="text-lg font-bold">
                            Messaging Angles
                        </h2>

                        <div class="mt-5 space-y-4">

                            @foreach($intel->messaging_angles ?? [] as $angle)

                                <div class="rounded-2xl border border-slate-100 p-4">

                                    <p class="font-semibold text-slate-800">
                                        {{ $angle['angle'] ?? '' }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $angle['reason'] ?? '' }}
                                    </p>

                                </div>

                            @endforeach

                        </div>

                    </section>

                </div>

                <aside class="space-y-6">

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">

                        <h2 class="font-bold">
                            Key Strengths
                        </h2>

                        <ul class="mt-4 space-y-3">

                            @foreach($intel->key_strengths ?? [] as $item)

                                <li class="flex gap-3 text-sm leading-6 text-slate-600">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                                    {{ $item }}
                                </li>

                            @endforeach

                        </ul>

                    </section>

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6">

                        <h2 class="font-bold">
                            Opportunities
                        </h2>

                        <ul class="mt-4 space-y-3">

                            @foreach($intel->opportunities ?? [] as $item)

                                <li class="flex gap-3 text-sm leading-6 text-slate-600">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-500"></span>
                                    {{ $item }}
                                </li>

                            @endforeach

                        </ul>

                    </section>

                    <section class="rounded-[28px] bg-slate-950 p-6 text-white">

                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                            Intelligence Engine
                        </p>

                        <p class="mt-4 text-sm text-slate-400">
                            Provider
                        </p>

                        <p class="mt-1 font-semibold">
                            {{ $intel->provider }}
                        </p>

                        <p class="mt-4 text-sm text-slate-400">
                            Model
                        </p>

                        <p class="mt-1 break-all text-xs leading-5">
                            {{ $intel->model }}
                        </p>

                        <p class="mt-4 text-sm text-slate-400">
                            Generated
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            {{ $intel->generated_at?->format('d M Y, h:i A') }}
                        </p>

                    </section>

                </aside>

            </div>

        @endif

    </div>

</x-app-layout>
