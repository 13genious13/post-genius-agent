<x-app-layout>




    <div class="space-y-7">




        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">
                Autonomous AI
            </p>




            <h1 class="mt-2 text-3xl font-bold text-slate-950">
                Agent Center
            </h1>




            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                Monitor autonomous campaign runs, agent delegation,
                tool usage and execution readiness.
            </p>
        </div>




        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">




            <x-stat-card
                title="Total Runs"
                :value="$stats['total']"
            />




            <x-stat-card
                title="Running"
                :value="$stats['running']"
            />




            <x-stat-card
                title="Completed"
                :value="$stats['completed']"
            />




            <x-stat-card
                title="Failed"
                :value="$stats['failed']"
            />




        </div>




        <section class="rounded-[30px] border border-indigo-100 bg-gradient-to-br from-indigo-50 via-white to-violet-50 p-6 sm:p-8">




            <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">




                <div>




                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                        Multi-Agent Orchestrator
                    </p>




                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Research → Strategy → Delegation
                    </h2>




                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                        The orchestrator validates campaign intelligence,
                        loads strategy actions and assigns each action to
                        the appropriate AI capability.
                    </p>




                </div>




                <div class="flex flex-wrap gap-2">




<span class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
Research Agent Active
</span>




                    <span class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
Strategy Agent Active
</span>




                    <span class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-bold text-amber-700">
Content Agent Next
</span>




                </div>




            </div>




        </section>




        <section>




            <div class="mb-4 flex items-center justify-between">




                <div>
                    <h2 class="text-xl font-bold">
                        Agent Runs
                    </h2>




                    <p class="mt-1 text-sm text-slate-500">
                        Recent autonomous workflow activity.
                    </p>
                </div>




            </div>




            <div class="space-y-4">




                @forelse($runs as $run)




                    <a
                        href="{{ route('agent-runs.show', $run) }}"
                        class="block rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md"
                    >




                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">




                            <div class="min-w-0">




                                <div class="flex flex-wrap items-center gap-3">




                                    <h3 class="font-bold text-slate-900">
                                        {{ $run->campaign?->name ?? 'Agent Run' }}
                                    </h3>




                                    <x-status-badge :status="$run->status" />




                                </div>




                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $run->business->name }}
                                    · {{ ucfirst($run->autonomy_mode) }}
                                </p>




                                <p class="mt-3 line-clamp-2 max-w-3xl text-sm leading-6 text-slate-600">
                                    {{ $run->objective }}
                                </p>




                            </div>




                            <div class="w-full lg:w-56">




                                <div class="flex justify-between text-xs font-semibold text-slate-500">
                                    <span>Progress</span>
                                    <span>{{ $run->progress }}%</span>
                                </div>




                                <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        class="h-full rounded-full bg-indigo-600"
                                        style="width: {{ min(100, $run->progress) }}%"
                                    ></div>
                                </div>




                            </div>




                        </div>




                    </a>




                @empty




                    <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">




                        <h3 class="text-lg font-bold">
                            No Agent Runs Yet
                        </h3>




                        <p class="mt-2 text-sm text-slate-500">
                            Open a campaign with completed research and strategy to start the Agent.
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
