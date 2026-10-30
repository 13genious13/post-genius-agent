<x-app-layout>




    <div class="space-y-7">




        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">




            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    Strategy Agent
                </p>




                <h1 class="mt-2 text-3xl font-bold">
                    {{ $campaign->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    Turn grounded campaign research into an executable marketing strategy.
                </p>
            </div>




            <a
                href="{{ route('campaigns.show', $campaign) }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
            >
                Campaign Overview
            </a>




        </div>




        @if($researchRuns->isEmpty())




            <section class="rounded-[28px] border border-amber-200 bg-amber-50 p-7">




                <h2 class="text-lg font-bold text-amber-900">
                    Research Required
                </h2>




                <p class="mt-2 text-sm leading-6 text-amber-800">
                    Complete a Research Agent run before generating the campaign strategy.
                </p>




                <a
                    href="{{ route('research.campaign', $campaign) }}"
                    class="mt-5 inline-flex rounded-xl bg-amber-900 px-5 py-3 text-sm font-semibold text-white"
                >
                    Open Research Agent
                </a>




            </section>




        @else




            <section class="rounded-[28px] border border-indigo-100 bg-gradient-to-br from-indigo-50 via-white to-violet-50 p-6 sm:p-8">




                <div class="grid gap-8 lg:grid-cols-2">




                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                            Generate Strategy
                        </p>




                        <h2 class="mt-2 text-xl font-bold">
                            Strategy Agent
                        </h2>




                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            The agent will combine the campaign, business intelligence and selected research evidence into an execution-ready strategy.
                        </p>
                    </div>




                    <form
                        method="POST"
                        action="{{ route('strategies.generate', $campaign) }}"
                        class="space-y-4"
                    >
                        @csrf




                        <div>
                            <label class="mb-2 block text-sm font-semibold">
                                Research Version
                            </label>




                            <select
                                name="research_run_id"
                                required
                                class="w-full rounded-xl border-slate-200"
                            >
                                @foreach($researchRuns as $research)




                                    <option
                                        value="{{ $research->id }}"
                                        @selected(
                                        old(
                                    'research_run_id',
                                    $campaign->latestResearch?->id
                                    ) == $research->id
                                    )
                                    >
                                    Research v{{ $research->version }}
                                    — {{ $research->source_count }} sources
                                    </option>




                                @endforeach
                            </select>
                        </div>




                        <div>
                            <label class="mb-2 block text-sm font-semibold">
                                Strategy Objective
                            </label>




                            <textarea
                                name="objective"
                                rows="5"
                                required
                                class="w-full rounded-2xl border-slate-200"
                            >{{ old(
'objective',
'Create an evidence-backed marketing strategy that achieves the campaign goal, prioritizes the strongest audience and message opportunities, defines the campaign funnel and produces an executable action plan.'
) }}</textarea>
                        </div>




                        <button class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                            Generate Strategy
                        </button>




                    </form>




                </div>




            </section>




        @endif




        <section>




            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold">
                    Strategy History
                </h2>




                <span class="text-xs text-slate-400">
{{ $strategies->total() }} versions
</span>
            </div>




            <div class="space-y-4">




                @forelse($strategies as $strategy)




                    <a
                        href="{{ route('strategies.show', $strategy) }}"
                        class="block rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200"
                    >




                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">




                            <div>




                                <div class="flex items-center gap-3">
                                    <h3 class="font-bold">
                                        Strategy v{{ $strategy->version }}
                                    </h3>




                                    <x-status-badge :status="$strategy->status" />
                                </div>




                                <p class="mt-2 line-clamp-2 text-sm text-slate-500">
                                    {{ $strategy->objective }}
                                </p>




                            </div>




                            <div class="text-sm text-slate-500">
                                Research v{{ $strategy->researchRun?->version ?? '—' }}
                            </div>




                        </div>




                    </a>




                @empty




                    <div class="rounded-[24px] border border-dashed border-slate-300 bg-white py-12 text-center">
                        No strategy versions yet.
                    </div>




                @endforelse




            </div>




            <div class="mt-6">
                {{ $strategies->links() }}
            </div>




        </section>




    </div>




</x-app-layout>
