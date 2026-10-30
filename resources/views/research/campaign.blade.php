<x-app-layout>




    <div class="space-y-7">




        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">



            <a
                href="{{ route('research.campaign', $campaign) }}"
                class="rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white"
            >
                Research Agent
            </a>

            <div>






                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    Research Agent
                </p>




                <h1 class="mt-2 text-3xl font-bold text-slate-950">
                    {{ $campaign->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    Research current market evidence before final campaign strategy.
                </p>




            </div>




            <a
                href="{{ route('campaigns.show', $campaign) }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
            >
                Campaign Overview
            </a>



            <div class="rounded-[28px] border border-violet-100 bg-violet-50 p-6">




                <p class="text-xs font-bold uppercase text-violet-600">
                    Live Research
                </p>




                @if($campaign->latestResearch)




                    <div class="mt-3 flex items-center justify-between gap-4">




                        <div>
                            <p class="font-bold text-slate-900">
                                Research v{{ $campaign->latestResearch->version }}
                            </p>




                            <p class="mt-1 text-sm text-slate-500">
                                {{ $campaign->latestResearch->source_count }}
                                sources collected
                            </p>
                        </div>




                        <x-status-badge :status="$campaign->latestResearch->status" />




                    </div>




                @else




                    <p class="mt-3 font-bold text-slate-900">
                        No Live Research Yet
                    </p>




                    <p class="mt-2 text-sm text-slate-500">
                        Run the Research Agent before finalizing autonomous strategy.
                    </p>




                @endif




            </div>


        </div>




        <section class="rounded-[28px] border border-indigo-100 bg-gradient-to-br from-indigo-50 via-white to-violet-50 p-6 sm:p-8">




            <div class="grid gap-8 lg:grid-cols-2">




                <div>




                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                        New Research Run
                    </p>




                    <h2 class="mt-2 text-xl font-bold">
                        What should the agent investigate?
                    </h2>




                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Describe the information required before campaign strategy is finalized.
                    </p>




                </div>




                <form
                    method="POST"
                    action="{{ route('research.start', $campaign) }}"
                    class="space-y-4"
                >
                    @csrf




                    <textarea
                        name="objective"
                        rows="5"
                        required
                        class="w-full rounded-2xl border-slate-200"
                        placeholder="Research current market demand, audience pain points, competitor positioning and marketing trends relevant to this campaign."
                    >{{ old(
'objective',
'Research current market conditions, audience needs, competitor positioning, trends and actionable opportunities relevant to this campaign.'
) }}</textarea>




                    <button class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                        Start Research Agent
                    </button>




                </form>




            </div>




        </section>




        <section>




            <div class="mb-4 flex items-center justify-between">




                <h2 class="text-lg font-bold">
                    Research History
                </h2>




                <span class="text-xs text-slate-400">
{{ $runs->total() }} runs
</span>




            </div>




            <div class="space-y-4">




                @forelse($runs as $run)




                    <a
                        href="{{ route('research.show', $run) }}"
                        class="block rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200"
                    >




                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">




                            <div>




                                <div class="flex items-center gap-3">




                                    <h3 class="font-bold">
                                        Research v{{ $run->version }}
                                    </h3>




                                    <x-status-badge :status="$run->status" />




                                </div>




                                <p class="mt-2 line-clamp-2 text-sm text-slate-500">
                                    {{ $run->objective }}
                                </p>




                            </div>




                            <div class="flex gap-6 text-sm">




                                <div>
                                    <p class="text-xs text-slate-400">Queries</p>
                                    <p class="mt-1 font-bold">{{ $run->query_count }}</p>
                                </div>




                                <div>
                                    <p class="text-xs text-slate-400">Sources</p>
                                    <p class="mt-1 font-bold">{{ $run->source_count }}</p>
                                </div>




                            </div>




                        </div>




                    </a>




                @empty




                    <div class="rounded-[24px] border border-dashed border-slate-300 bg-white py-12 text-center">




                        <p class="font-semibold">
                            No research runs yet.
                        </p>




                    </div>




                @endforelse




            </div>




            <div class="mt-6">
                {{ $runs->links() }}
            </div>




        </section>




    </div>




</x-app-layout>
