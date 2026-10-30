<x-app-layout>




    <div class="space-y-6">




        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">




            <div>




                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    {{ str($marketingGoal->goal_type)->replace('_', ' ')->title() }}
                </p>




                <h1 class="mt-2 text-3xl font-bold text-slate-950">
                    {{ $marketingGoal->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    {{ $marketingGoal->business->name }}
                </p>




            </div>




            <div class="flex flex-wrap gap-3">




                <a
                    href="{{ route('marketing-goals.edit', $marketingGoal) }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
                >
                    Edit Goal
                </a>




                <a
                    href="{{ route('campaigns.create', ['goal' => $marketingGoal->id]) }}"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white"
                >
                    Create Campaign
                </a>




            </div>




        </div>




        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">




            <x-stat-card
                title="Status"
                :value="ucfirst($marketingGoal->status)"
                description="Current goal state"
            />




            <x-stat-card
                title="Primary KPI"
                :value="$marketingGoal->primary_kpi ?: 'Not Set'"
                description="Primary measurement"
            />




            <x-stat-card
                title="Target"
                :value="$marketingGoal->target_value !== null
        ? number_format((float) $marketingGoal->target_value, 0)
        : '—'"
                :description="$marketingGoal->unit ?? ''"
            />




            <x-stat-card
                title="Campaigns"
                :value="$marketingGoal->campaigns->count()"
                description="Campaigns linked to this goal"
            />




        </div>




        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">




            <section class="xl:col-span-2 rounded-[28px] border border-slate-200 bg-white p-6">




                <h2 class="text-lg font-bold">
                    Goal Definition
                </h2>




                <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">
                    {{ $marketingGoal->description ?: 'No description supplied.' }}
                </p>




                @if($marketingGoal->success_definition)




                    <div class="mt-6 rounded-2xl bg-emerald-50 p-5">




                        <p class="text-xs font-bold uppercase text-emerald-700">
                            Success Definition
                        </p>




                        <p class="mt-2 text-sm leading-6 text-emerald-900">
                            {{ $marketingGoal->success_definition }}
                        </p>




                    </div>




                @endif




            </section>




            <section class="rounded-[28px] bg-slate-950 p-6 text-white">




                <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                    Goal Context
                </p>




                <div class="mt-5 space-y-5">




                    <div>
                        <p class="text-xs text-slate-500">Priority</p>
                        <p class="mt-1 font-semibold">
                            {{ $marketingGoal->priority }}/5
                        </p>
                    </div>




                    <div>
                        <p class="text-xs text-slate-500">Budget</p>
                        <p class="mt-1 font-semibold">
                            {{ $marketingGoal->budget !== null
    ? ($marketingGoal->currency ?? 'INR') . ' ' .
        number_format((float) $marketingGoal->budget, 2)
    : 'Not Set' }}
                        </p>
                    </div>




                    <div>
                        <p class="text-xs text-slate-500">Timeline</p>
                        <p class="mt-1 text-sm font-semibold">
                            {{ $marketingGoal->start_date?->format('d M Y') ?? '—' }}
                            →
                            {{ $marketingGoal->end_date?->format('d M Y') ?? '—' }}
                        </p>
                    </div>




                </div>




            </section>




        </div>




    </div>




</x-app-layout>
