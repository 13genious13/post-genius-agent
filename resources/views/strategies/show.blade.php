<x-app-layout>




    @php
        $s = $strategy;
    @endphp




    <div
        class="space-y-7"
        @if($s->isRunning())
        x-data
        x-init="setTimeout(() => window.location.reload(), 5000)"
        @endif
    >




        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">




            <div>




                <div class="flex flex-wrap items-center gap-3">




                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                        Strategy Agent
                    </p>




                    <x-status-badge :status="$s->status" />




                </div>




                <h1 class="mt-2 text-3xl font-bold">
                    {{ $s->campaign->name }}
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    Strategy v{{ $s->version }}
                    · Research v{{ $s->researchRun?->version ?? '—' }}
                </p>




            </div>




            <div class="flex flex-wrap gap-3">




                <a
                    href="{{ route('strategies.evidence', $s) }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold"
                >
                    Evidence Map
                </a>




                <a
                    href="{{ route('strategies.campaign', $s->campaign) }}"
                    class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white"
                >
                    Strategy History
                </a>




            </div>




        </div>




        @if($s->isRunning())




            <div class="rounded-[28px] border border-indigo-100 bg-indigo-50 p-6">




                <div class="flex items-center gap-4">




                    <div class="h-3 w-3 animate-pulse rounded-full bg-indigo-600"></div>




                    <div>
                        <h2 class="font-bold text-indigo-900">
                            Strategy Agent is working
                        </h2>




                        <p class="mt-1 text-sm text-indigo-700">
                            Current stage:
                            {{ ucfirst($s->status) }}.
                            This page refreshes automatically.
                        </p>
                    </div>




                </div>




            </div>




        @endif




        @if($s->status === 'failed')




            <div class="rounded-[28px] border border-red-200 bg-red-50 p-6">




                <h2 class="font-bold text-red-800">
                    Strategy Generation Failed
                </h2>




                <p class="mt-2 text-sm text-red-700">
                    {{ $s->error_message }}
                </p>




            </div>




        @endif




        @if($s->status === 'completed')




            <section class="rounded-[30px] bg-slate-950 p-7 text-white sm:p-9">




                <p class="text-xs font-bold uppercase tracking-wider text-indigo-300">
                    Strategic Thesis
                </p>




                <h2 class="mt-4 max-w-4xl text-2xl font-bold leading-9">
                    {{ $s->strategic_thesis }}
                </h2>




                <p class="mt-5 max-w-4xl text-sm leading-7 text-slate-300">
                    {{ $s->executive_summary }}
                </p>




                <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-4">




                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs text-slate-400">
                            Actions
                        </p>




                        <p class="mt-2 text-2xl font-bold">
                            {{ $s->actions->count() }}
                        </p>
                    </div>




                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs text-slate-400">
                            Evidence Links
                        </p>




                        <p class="mt-2 text-2xl font-bold">
                            {{ $s->evidence->count() }}
                        </p>
                    </div>




                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs text-slate-400">
                            Tokens
                        </p>




                        <p class="mt-2 text-2xl font-bold">
                            {{ number_format($s->total_tokens ?? 0) }}
                        </p>
                    </div>




                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs text-slate-400">
                            Model
                        </p>




                        <p class="mt-2 break-words text-xs font-semibold">
                            {{ $s->ai_model }}
                        </p>
                    </div>




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6 sm:p-8">




                <p class="text-xs font-bold uppercase text-indigo-600">
                    Positioning
                </p>




                <h2 class="mt-2 text-xl font-bold">
                    {{ data_get($s->positioning, 'market_position', 'Campaign Positioning') }}
                </h2>




                <p class="mt-4 text-sm leading-7 text-slate-600">
                    {{ data_get($s->positioning, 'value_proposition') }}
                </p>




                <div class="mt-6 grid gap-6 lg:grid-cols-2">




                    <div>
                        <h3 class="font-bold">
                            Differentiators
                        </h3>




                        <div class="mt-3 space-y-2">




                            @foreach(data_get($s->positioning, 'differentiators', []) as $item)
                                <p class="text-sm text-slate-600">
                                    • {{ $item }}
                                </p>
                            @endforeach




                        </div>
                    </div>




                    <div>
                        <h3 class="font-bold">
                            Proof Points
                        </h3>




                        <div class="mt-3 space-y-2">




                            @foreach(data_get($s->positioning, 'proof_points', []) as $item)
                                <p class="text-sm text-slate-600">
                                    • {{ $item }}
                                </p>
                            @endforeach




                        </div>
                    </div>




                </div>




            </section>




            <section>




                <h2 class="mb-4 text-xl font-bold">
                    Audience Strategy
                </h2>




                <div class="grid gap-5 lg:grid-cols-2">




                    @foreach($s->audience_strategy ?? [] as $audience)




                        <div class="rounded-[26px] border border-slate-200 bg-white p-6">




                            <div class="flex items-center justify-between gap-3">




                                <h3 class="font-bold">
                                    {{ $audience['segment'] ?? '' }}
                                </h3>




                                <span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600">
{{ ucfirst($audience['priority'] ?? 'secondary') }}
</span>




                            </div>




                            <p class="mt-4 text-xs font-bold uppercase text-slate-400">
                                Problem
                            </p>




                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                {{ $audience['problem'] ?? '' }}
                            </p>




                            <p class="mt-4 text-xs font-bold uppercase text-slate-400">
                                Message Angle
                            </p>




                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                {{ $audience['message_angle'] ?? '' }}
                            </p>




                            <div class="mt-4 flex flex-wrap gap-2">




                                @foreach($audience['source_ids'] ?? [] as $sourceId)




                                    <a
                                        href="{{ route('research.sources', $s->researchRun) }}#source-{{ $sourceId }}"
                                        class="rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-semibold text-indigo-600"
                                    >
                                        Source {{ $sourceId }}
                                    </a>




                                @endforeach




                            </div>




                        </div>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                <h2 class="text-xl font-bold">
                    Messaging Framework
                </h2>




                <div class="mt-5 rounded-2xl bg-indigo-50 p-5">




                    <p class="text-xs font-bold uppercase text-indigo-600">
                        Core Message
                    </p>




                    <p class="mt-2 font-semibold leading-7 text-slate-800">
                        {{ data_get($s->messaging_framework, 'core_message') }}
                    </p>




                </div>




                <div class="mt-6 grid gap-6 lg:grid-cols-2">




                    <div>
                        <h3 class="font-bold">
                            Supporting Messages
                        </h3>




                        <div class="mt-3 space-y-2">




                            @foreach(data_get($s->messaging_framework, 'supporting_messages', []) as $item)
                                <p class="text-sm text-slate-600">
                                    • {{ $item }}
                                </p>
                            @endforeach




                        </div>
                    </div>




                    <div>
                        <h3 class="font-bold">
                            CTA Strategy
                        </h3>




                        <div class="mt-3 space-y-2">




                            @foreach(data_get($s->messaging_framework, 'cta_strategy', []) as $item)
                                <p class="text-sm text-slate-600">
                                    • {{ $item }}
                                </p>
                            @endforeach




                        </div>
                    </div>




                </div>




            </section>




            <section>




                <h2 class="mb-4 text-xl font-bold">
                    Content Pillars
                </h2>




                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">




                    @foreach($s->content_pillars ?? [] as $pillar)




                        <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                            <div class="flex items-center justify-between gap-3">




                                <h3 class="font-bold">
                                    {{ $pillar['name'] ?? '' }}
                                </h3>




                                <span class="text-xs font-semibold text-indigo-600">
{{ ucfirst($pillar['priority'] ?? '') }}
</span>




                            </div>




                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                {{ $pillar['purpose'] ?? '' }}
                            </p>




                            <div class="mt-4 space-y-2">




                                @foreach($pillar['topics'] ?? [] as $topic)
                                    <p class="text-sm text-slate-700">
                                        • {{ $topic }}
                                    </p>
                                @endforeach




                            </div>




                        </div>




                    @endforeach




                </div>




            </section>




            <section>




                <h2 class="mb-4 text-xl font-bold">
                    Channel Strategy
                </h2>




                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">




                    @foreach($s->channel_strategy ?? [] as $channel)




                        <div class="rounded-[24px] border border-slate-200 bg-white p-5">




                            <h3 class="font-bold">
                                {{ $channel['platform'] ?? '' }}
                            </h3>




                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                {{ $channel['role'] ?? '' }}
                            </p>




                            <div class="mt-4 rounded-xl bg-slate-50 p-3">




                                <p class="text-xs text-slate-400">
                                    Primary KPI
                                </p>




                                <p class="mt-1 text-sm font-semibold">
                                    {{ $channel['primary_kpi'] ?? '' }}
                                </p>




                            </div>




                        </div>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                <h2 class="text-xl font-bold">
                    Campaign Funnel
                </h2>




                <div class="mt-6 grid gap-4 lg:grid-cols-4">




                    @foreach($s->funnel_strategy ?? [] as $stage)




                        <div class="rounded-2xl bg-slate-50 p-5">




                            <p class="text-xs font-bold uppercase text-indigo-600">
                                {{ $stage['stage'] ?? '' }}
                            </p>




                            <p class="mt-3 text-sm font-semibold">
                                {{ $stage['objective'] ?? '' }}
                            </p>




                            <p class="mt-3 text-xs leading-5 text-slate-500">
                                {{ $stage['message'] ?? '' }}
                            </p>




                            <p class="mt-4 text-xs font-semibold text-slate-700">
                                CTA:
                                {{ $stage['cta'] ?? '' }}
                            </p>




                        </div>




                    @endforeach




                </div>




            </section>




            <section>




                <h2 class="mb-4 text-xl font-bold">
                    Campaign Phases
                </h2>




                <div class="space-y-4">




                    @foreach($s->campaign_phases ?? [] as $phase)




                        <div class="rounded-[24px] border border-slate-200 bg-white p-6">




                            <div class="flex gap-4">




                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 font-bold text-white">
                                    {{ $phase['sequence'] ?? $loop->iteration }}
                                </div>




                                <div class="min-w-0">




                                    <h3 class="font-bold">
                                        {{ $phase['name'] ?? '' }}
                                    </h3>




                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $phase['objective'] ?? '' }}
                                    </p>




                                    <p class="mt-2 text-xs font-semibold text-indigo-600">
                                        {{ $phase['duration_guidance'] ?? '' }}
                                    </p>




                                    <div class="mt-4 space-y-2">




                                        @foreach($phase['activities'] ?? [] as $activity)
                                            <p class="text-sm text-slate-600">
                                                • {{ $activity }}
                                            </p>
                                        @endforeach




                                    </div>




                                </div>




                            </div>




                        </div>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6">




                <h2 class="text-xl font-bold">
                    Execution Actions
                </h2>




                <div class="mt-5 space-y-3">




                    @foreach($s->actions as $action)




                        <div class="rounded-2xl border border-slate-100 p-5">




                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">




                                <div>




                                    <div class="flex flex-wrap gap-2">




<span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600">
{{ ucfirst($action->action_type) }}
</span>




                                        <span class="rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-semibold">
{{ ucfirst($action->priority) }}
</span>




                                    </div>




                                    <h3 class="mt-3 font-bold">
                                        {{ $action->title }}
                                    </h3>




                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        {{ $action->description }}
                                    </p>




                                </div>




                                <x-status-badge :status="$action->status" />




                            </div>




                        </div>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-violet-100 bg-violet-50 p-6">




                <p class="text-xs font-bold uppercase text-violet-600">
                    Explain Strategy
                </p>




                <h2 class="mt-2 text-xl font-bold text-violet-950">
                    Why the Agent Chose This Strategy
                </h2>




                <div class="mt-5 space-y-4">




                    @foreach($s->agent_explanation ?? [] as $item)




                        <div class="rounded-2xl bg-white/80 p-5">




                            <p class="font-bold">
                                {{ $item['decision'] ?? '' }}
                            </p>




                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                {{ $item['because'] ?? '' }}
                            </p>




                            <p class="mt-3 text-xs font-semibold text-violet-700">
                                Expected:
                                {{ $item['expected_result'] ?? '' }}
                            </p>




                        </div>




                    @endforeach




                </div>




            </section>




        @endif




    </div>




</x-app-layout>
