<x-app-layout>




    <div class="space-y-7">




        <div>




            <a
                href="{{ route('strategies.show', $strategy) }}"
                class="text-sm font-semibold text-indigo-600"
            >
                ← Strategy Report
            </a>




            <h1 class="mt-4 text-3xl font-bold">
                Strategy Evidence Map
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Shows which research sources influenced strategic decisions.
            </p>




        </div>




        @forelse(
        $strategy->evidence->groupBy('decision_key')
        as $decisionKey => $items
        )




            <section class="rounded-[26px] border border-slate-200 bg-white p-6">




                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                    {{ str($decisionKey)->replace('_', ' ')->title() }}
                </p>




                <h2 class="mt-2 text-lg font-bold">
                    {{ $items->first()->decision_summary }}
                </h2>




                <div class="mt-5 space-y-3">




                    @foreach($items as $evidence)




                        <a
                            href="{{ route('research.sources', $strategy->researchRun) }}#source-{{ $evidence->research_source_id }}"
                            class="block rounded-2xl bg-slate-50 p-4 transition hover:bg-indigo-50"
                        >




                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">




                                <div>




                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $evidence->source?->title ?: $evidence->source?->domain }}
                                    </p>




                                    <p class="mt-1 text-xs text-slate-400">
                                        Source {{ $evidence->research_source_id }}
                                        · {{ $evidence->source?->domain }}
                                    </p>




                                </div>




                                <span class="rounded-lg bg-white px-2.5 py-1 text-xs font-semibold text-indigo-600">
{{ ucfirst($evidence->evidence_role) }}
</span>




                            </div>




                        </a>




                    @endforeach




                </div>




            </section>




        @empty




            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">




                <p class="font-semibold">
                    No evidence mapping available.
                </p>




            </div>




        @endforelse




    </div>




</x-app-layout>
