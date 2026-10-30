<x-app-layout>




    <div class="space-y-7">




        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">




            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    Strategy
                </p>




                <h1 class="mt-2 text-3xl font-bold text-slate-950">
                    Marketing Goals
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    Define measurable outcomes before autonomous agents begin planning campaigns.
                </p>
            </div>




            <a
                href="{{ route('marketing-goals.create') }}"
                class="rounded-xl bg-slate-950 px-5 py-3 text-center text-sm font-semibold text-white hover:bg-indigo-600"
            >
                + New Goal
            </a>




        </div>




        @if($goals->isEmpty())




            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">




                <h2 class="text-xl font-bold">
                    No marketing goals yet
                </h2>




                <p class="mt-2 text-sm text-slate-500">
                    Create the first measurable business objective.
                </p>




            </div>




        @else




            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">




                @foreach($goals as $goal)




                    <a
                        href="{{ route('marketing-goals.show', $goal) }}"
                        class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md"
                    >




                        <div class="flex items-start justify-between gap-4">




                            <div>
                                <p class="text-xs font-bold uppercase text-indigo-600">
                                    {{ str($goal->goal_type)->replace('_', ' ')->title() }}
                                </p>




                                <h2 class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $goal->name }}
                                </h2>




                                <p class="mt-1 text-sm text-slate-400">
                                    {{ $goal->business->name }}
                                </p>
                            </div>




                            <x-status-badge :status="$goal->status" />




                        </div>




                        @if($goal->primary_kpi)




                            <div class="mt-6 rounded-2xl bg-slate-50 p-4">




                                <p class="text-xs text-slate-400">
                                    Primary KPI
                                </p>




                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $goal->primary_kpi }}




                                    @if($goal->target_value)
                                        · {{ number_format($goal->target_value, 0) }}
                                        {{ $goal->unit }}
                                    @endif
                                </p>




                            </div>




                        @endif




                    </a>




                @endforeach




            </div>




            {{ $goals->links() }}




        @endif
    </div>
</x-app-layout>
