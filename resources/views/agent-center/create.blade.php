<x-app-layout>




    <div class="mx-auto max-w-5xl space-y-7">




        <div>




            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">
                Agent Center
            </p>




            <h1 class="mt-2 text-3xl font-bold">
                Start Autonomous Run
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                {{ $campaign->name }}
            </p>




        </div>




        <div class="grid gap-5 md:grid-cols-3">




            <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                <p class="text-xs font-bold uppercase text-slate-400">
                    Research
                </p>




                @if(
                $campaign->latestResearch
                && $campaign->latestResearch->status === 'completed'
                )


                    <a
                        href="{{ route('agent-runs.create', $campaign) }}"
                        class="inline-flex rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-3 text-sm font-bold text-white shadow-sm"
                    >
                        Run Agent
                    </a>

                    <a
                        href="{{ route('agent-controls.edit', $campaign->business) }}"
                        class="inline-flex rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
                    >
                        Agent Controls
                    </a>

                    <p class="mt-3 font-bold text-emerald-700">
                        Ready
                    </p>




                    <p class="mt-1 text-sm text-slate-500">
                        Research v{{ $campaign->latestResearch->version }}
                    </p>




                @else




                    <p class="mt-3 font-bold text-amber-700">
                        Required
                    </p>




                @endif




            </div>




            <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                <p class="text-xs font-bold uppercase text-slate-400">
                    Strategy
                </p>




                @if(
                $campaign->latestStrategy
                && $campaign->latestStrategy->status === 'completed'
                )




                    <p class="mt-3 font-bold text-emerald-700">
                        Ready
                    </p>




                    <p class="mt-1 text-sm text-slate-500">
                        Strategy v{{ $campaign->latestStrategy->version }}
                    </p>




                @else




                    <p class="mt-3 font-bold text-amber-700">
                        Required
                    </p>




                @endif




            </div>




            <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                <p class="text-xs font-bold uppercase text-slate-400">
                    Autonomy
                </p>




                <p class="mt-3 font-bold text-indigo-700">
                    {{ ucfirst(
                    $campaign->business->agentControl?->autonomy_mode
                    ?? 'assisted'
                    ) }}
                </p>




                <a
                    href="{{ route('agent-controls.edit', $campaign->business) }}"
                    class="mt-2 inline-block text-xs font-bold text-indigo-600"
                >
                    Configure Controls
                </a>




            </div>




        </div>




        <section class="rounded-[30px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">




            <h2 class="text-xl font-bold">
                Run Objective
            </h2>




            <p class="mt-2 text-sm leading-6 text-slate-500">
                Tell the Agent what this execution run should accomplish.
            </p>




            <form
                method="POST"
                action="{{ route('agent-runs.store', $campaign) }}"
                class="mt-6 space-y-5"
            >
                @csrf




                <textarea
                    name="objective"
                    rows="7"
                    required
                    class="w-full rounded-2xl border-slate-200"
                >{{ old(
'objective',
'Execute the approved campaign strategy by analyzing all planned actions, assigning each action to the appropriate agent capability and preparing the campaign for content and creative execution.'
) }}</textarea>




                @if(
                !$campaign->latestResearch
                || !$campaign->latestStrategy
                )




                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        Complete both Research and Strategy before starting an Agent run.
                    </div>




                @endif




                <button
                    @disabled(
                    !$campaign->latestResearch
                    || !$campaign->latestStrategy
                    )
                    class="w-full rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                    Run Agent
                </button>




            </form>




        </section>




    </div>




</x-app-layout>
