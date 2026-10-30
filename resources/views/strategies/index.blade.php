<x-app-layout>




    <div class="space-y-7">




        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                AI Team
            </p>




            <h1 class="mt-2 text-3xl font-bold text-slate-950">
                Strategy Agent
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Evidence-backed campaign strategies generated from business intelligence, goals and live research.
            </p>
        </div>




        <div class="space-y-4">




            @forelse($strategies as $strategy)




                <a
                    href="{{ route('strategies.show', $strategy) }}"
                    class="block rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md"
                >




                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">




                        <div>




                            <div class="flex flex-wrap items-center gap-3">




                                <h2 class="text-lg font-bold">
                                    {{ $strategy->campaign->name }}
                                </h2>




                                <x-status-badge :status="$strategy->status" />




                            </div>




                            <p class="mt-1 text-sm text-slate-400">
                                {{ $strategy->business->name }}
                                · Strategy v{{ $strategy->version }}
                            </p>




                            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-500">
                                {{ $strategy->objective }}
                            </p>




                        </div>




                        <div class="grid grid-cols-2 gap-3">




                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-400">
                                    Research
                                </p>




                                <p class="mt-1 font-bold">
                                    v{{ $strategy->researchRun?->version ?? '—' }}
                                </p>
                            </div>




                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-400">
                                    Strategy
                                </p>




                                <p class="mt-1 font-bold">
                                    v{{ $strategy->version }}
                                </p>
                            </div>




                        </div>




                    </div>




                </a>




            @empty




                <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">




                    <h2 class="text-xl font-bold">
                        No Strategies Yet
                    </h2>




                    <p class="mt-2 text-sm text-slate-500">
                        Complete campaign research before generating a strategy.
                    </p>




                </div>




            @endforelse




        </div>




        {{ $strategies->links() }}




    </div>




</x-app-layout>
