<x-app-layout>




    <div class="space-y-7">




        <div>




            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                AI Team
            </p>




            <h1 class="mt-2 text-3xl font-bold">
                Research Agent
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Live market research, evidence collection and campaign intelligence.
            </p>




        </div>




        <div class="grid grid-cols-1 gap-5">




            @forelse($runs as $run)




                <a
                    href="{{ route('research.show', $run) }}"
                    class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md"
                >




                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">




                        <div>




                            <div class="flex flex-wrap items-center gap-3">




                                <h2 class="text-lg font-bold">
                                    {{ $run->campaign->name }}
                                </h2>




                                <x-status-badge :status="$run->status" />




                            </div>




                            <p class="mt-1 text-sm text-slate-400">
                                {{ $run->business->name }}
                                · Research v{{ $run->version }}
                            </p>




                            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-500">
                                {{ $run->objective }}
                            </p>




                        </div>




                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">




                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-400">Queries</p>
                                <p class="mt-1 font-bold">{{ $run->query_count }}</p>
                            </div>




                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-400">Sources</p>
                                <p class="mt-1 font-bold">{{ $run->source_count }}</p>
                            </div>




                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-400">Successful</p>
                                <p class="mt-1 font-bold">{{ $run->successful_queries }}</p>
                            </div>




                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-400">Version</p>
                                <p class="mt-1 font-bold">{{ $run->version }}</p>
                            </div>




                        </div>




                    </div>




                </a>




            @empty




                <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">




                    <h2 class="text-xl font-bold">
                        No Research Activity
                    </h2>




                    <p class="mt-2 text-sm text-slate-500">
                        Start research from one of your campaigns.
                    </p>




                </div>




            @endforelse




        </div>




        {{ $runs->links() }}




    </div>




</x-app-layout>
