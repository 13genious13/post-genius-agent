<x-app-layout>




    <div
        class="space-y-7"
        @if($run->isRunning())
        x-data
        x-init="setTimeout(() => window.location.reload(), 4000)"
        @endif
    >




        <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">




            <div>




                <div class="flex flex-wrap items-center gap-3">




                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">
                        Agent Run
                    </p>




                    <x-status-badge :status="$run->status" />




                </div>




                <h1 class="mt-2 text-3xl font-bold">
                    {{ $run->campaign?->name ?? 'Autonomous Run' }}
                </h1>




                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    {{ $run->objective }}
                </p>




            </div>




            <div class="flex flex-wrap gap-3">




                @if($run->campaign)




                    <a
                        href="{{ route('campaigns.show', $run->campaign) }}"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
                    >
                        Campaign
                    </a>




                @endif




                <a
                    href="{{ route('agent-center.index') }}"
                    class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white"
                >
                    Agent Center
                </a>




            </div>




        </div>




        <section class="rounded-[30px] bg-slate-950 p-7 text-white sm:p-8">




            <div class="grid gap-7 lg:grid-cols-[1fr_320px] lg:items-center">




                <div>




                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                        Current State
                    </p>




                    <h2 class="mt-3 text-2xl font-bold">
                        {{ $run->isRunning()
                        ? str($run->current_stage)->replace('_', ' ')->title()
                        : str($run->status)->title()
                        }}
                    </h2>




                    <p class="mt-3 text-sm text-slate-300">
                        Mode:
                        {{ ucfirst($run->autonomy_mode) }}




                        @if($run->current_agent)
                            · Agent:
                            {{ str($run->current_agent)->replace('_', ' ')->title() }}
                        @endif
                    </p>




                </div>




                <div>




                    <div class="flex justify-between text-xs font-semibold text-slate-300">
                        <span>Progress</span>
                        <span>{{ $run->progress }}%</span>
                    </div>




                    <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/10">
                        <div
                            class="h-full rounded-full bg-indigo-400 transition-all"
                            style="width: {{ min(100, $run->progress) }}%"
                        ></div>
                    </div>




                </div>




            </div>




        </section>




        @if($run->status === 'failed')




            <div class="rounded-[26px] border border-red-200 bg-red-50 p-6">




                <h2 class="font-bold text-red-800">
                    Agent Run Failed
                </h2>




                <p class="mt-2 text-sm text-red-700">
                    {{ $run->error_message }}
                </p>




            </div>




        @endif




        @if($run->result)




            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">




                <x-stat-card
                    title="Tasks"
                    :value="$run->result['task_count'] ?? 0"
                />




                <x-stat-card
                    title="Ready"
                    :value="$run->result['ready'] ?? 0"
                />




                <x-stat-card
                    title="Waiting Capability"
                    :value="$run->result['waiting_capability'] ?? 0"
                />




                <x-stat-card
                    title="Needs Review"
                    :value="$run->result['needs_review'] ?? 0"
                />




            </div>




        @endif




        <div class="grid gap-7 xl:grid-cols-[1.2fr_.8fr]">




            <section>




                <div class="mb-4">




                    <h2 class="text-xl font-bold">
                        Execution Tasks
                    </h2>




                    <p class="mt-1 text-sm text-slate-500">
                        Actions delegated by the orchestrator.
                    </p>




                </div>




                <div class="space-y-4">




                    @forelse($run->tasks as $task)


                        @if($task->agent_key === 'content_agent' && in_array($task->status, ['ready','failed']))
                            <form method="POST" action="{{ route('agent-tasks.generate-content', $task) }}">
                                @csrf
                                <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Generate content</button>
                            </form>
                        @endif

                        <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">




                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">




                                <div class="min-w-0">




                                    <div class="flex flex-wrap gap-2">




<span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-600">
{{ str($task->agent_key)->replace('_', ' ')->title() }}
</span>




                                        <span class="rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">
{{ ucfirst($task->priority) }}
</span>




                                        @if($task->requires_approval)




                                            <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
Approval Required
</span>




                                        @endif




                                    </div>




                                    <h3 class="mt-3 font-bold text-slate-900">
                                        {{ $task->title }}
                                    </h3>




                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        {{ $task->description }}
                                    </p>




                                    @if(data_get($task->metadata, 'reason'))




                                        <p class="mt-3 text-xs text-slate-400">
                                            {{ data_get($task->metadata, 'reason') }}
                                        </p>




                                    @endif




                                </div>




                                <x-status-badge :status="$task->status" />




                            </div>




                        </div>




                    @empty




                        <div class="rounded-[24px] border border-dashed border-slate-300 bg-white py-12 text-center text-sm text-slate-500">
                            Tasks will appear when orchestration reaches the delegation stage.
                        </div>




                    @endforelse




                </div>




            </section>




            <section>




                <div class="mb-4">




                    <h2 class="text-xl font-bold">
                        Agent Activity
                    </h2>




                    <p class="mt-1 text-sm text-slate-500">
                        Live orchestration timeline.
                    </p>




                </div>




                <div class="rounded-[26px] border border-slate-200 bg-white p-5">




                    <div class="space-y-5">




                        @forelse($run->activities as $activity)




                            <div class="relative pl-7">




                                <div
                                    class="absolute left-0 top-1.5 h-3 w-3 rounded-full
{{
$activity->level === 'success'
? 'bg-emerald-500'
: (
$activity->level === 'error'
? 'bg-red-500'
: (
$activity->level === 'warning'
? 'bg-amber-500'
: 'bg-indigo-500'
)
)
}}"
                                ></div>




                                <div class="absolute bottom-[-20px] left-[5px] top-4 w-px bg-slate-100"></div>




                                <div class="flex items-start justify-between gap-3">




                                    <div>




                                        <p class="text-sm font-bold text-slate-800">
                                            {{ $activity->title }}
                                        </p>




                                        @if($activity->message)




                                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                                {{ $activity->message }}
                                            </p>




                                        @endif




                                        @if($activity->agent_key)




                                            <p class="mt-2 text-[11px] font-bold uppercase tracking-wider text-indigo-500">
                                                {{ str($activity->agent_key)->replace('_', ' ')->title() }}
                                            </p>




                                        @endif




                                    </div>




                                    <span class="shrink-0 text-[11px] text-slate-400">
{{ $activity->created_at->format('H:i:s') }}
</span>




                                </div>




                            </div>




                        @empty




                            <p class="py-8 text-center text-sm text-slate-500">
                                Waiting for agent activity.
                            </p>




                        @endforelse




                    </div>




                </div>




            </section>




        </div>




        <section class="rounded-[28px] border border-slate-200 bg-white p-6">




            <div class="flex items-center justify-between">




                <div>
                    <h2 class="text-xl font-bold">
                        Tool Calls
                    </h2>




                    <p class="mt-1 text-sm text-slate-500">
                        Auditable tools used during this run.
                    </p>
                </div>




                <span class="text-sm font-bold text-indigo-600">
{{ $run->toolCalls->count() }}
</span>




            </div>




            <div class="mt-5 overflow-x-auto">




                <table class="min-w-full text-left text-sm">




                    <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase text-slate-400">
                        <th class="px-3 py-3">Tool</th>
                        <th class="px-3 py-3">Agent</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3">Latency</th>
                    </tr>
                    </thead>




                    <tbody>




                    @foreach($run->toolCalls as $call)




                        <tr class="border-b border-slate-50">




                            <td class="px-3 py-4 font-semibold">
                                {{ str($call->tool_key)->replace('_', ' ')->title() }}
                            </td>




                            <td class="px-3 py-4 text-slate-500">
                                {{ str($call->agent_key ?? 'orchestrator')->replace('_', ' ')->title() }}
                            </td>




                            <td class="px-3 py-4">
                                <x-status-badge :status="$call->status" />
                            </td>




                            <td class="px-3 py-4 text-slate-500">
                                {{ $call->latency_ms !== null
                                ? $call->latency_ms.' ms'
                                : '—'
                                }}
                            </td>




                        </tr>




                    @endforeach




                    </tbody>




                </table>




            </div>




        </section>




    </div>




</x-app-layout>
