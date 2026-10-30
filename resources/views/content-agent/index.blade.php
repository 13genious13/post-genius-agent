<x-app-layout>
<div class="mx-auto max-w-7xl space-y-7 px-3 py-5 sm:px-6">
    <header class="rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-indigo-700 p-6 text-white sm:p-9">
        <p class="text-xs font-semibold uppercase tracking-[.2em] text-cyan-300">PostGenius · AI Team</p>
        <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Content Agent Workspace</h1>
        <p class="mt-3 max-w-2xl text-sm text-indigo-100">Create campaign-aware content tasks, generate AI drafts, edit copy and send it for human approval. Nothing is published automatically here.</p>
    </header>
    @if(session('success'))<div class="rounded-xl bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="rounded-xl bg-red-50 p-4 text-red-800">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="rounded-xl bg-red-50 p-4 text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div class="grid gap-6 lg:grid-cols-5">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-bold text-slate-900">New content task</h2>
            <p class="mt-1 text-sm text-slate-500">Select an existing agent run with a campaign.</p>
            <form method="POST" action="{{ route('content-agent.store') }}" class="mt-5 space-y-4">@csrf
                <label class="block text-sm font-semibold">Campaign / Agent Run<select name="agent_run_id" required class="mt-1 w-full rounded-xl border-slate-300"><option value="">Select a run</option>@foreach($runs as $run)<option value="{{ $run->id }}" @selected(old('agent_run_id')==$run->id)>{{ $run->campaign?->name }} — {{ $run->business?->name }} (#{{ $run->id }})</option>@endforeach</select></label>
                <label class="block text-sm font-semibold">Task title<input name="title" value="{{ old('title') }}" required maxlength="180" class="mt-1 w-full rounded-xl border-slate-300" placeholder="Launch announcement"></label>
                <label class="block text-sm font-semibold">Content type<select name="task_type" class="mt-1 w-full rounded-xl border-slate-300">@foreach(['social_post'=>'Social post','caption'=>'Caption','blog'=>'Blog','ad_copy'=>'Ad copy','email'=>'Email'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                <label class="block text-sm font-semibold">Platform<select name="platform" class="mt-1 w-full rounded-xl border-slate-300">@foreach(['generic','facebook','instagram','linkedin','x','youtube','blog'] as $platform)<option value="{{ $platform }}">{{ ucfirst($platform) }}</option>@endforeach</select></label>
                <label class="block text-sm font-semibold">Brief<textarea name="description" rows="2" maxlength="3000" class="mt-1 w-full rounded-xl border-slate-300">{{ old('description') }}</textarea></label>
                <label class="block text-sm font-semibold">Additional instructions<textarea name="instructions" rows="3" maxlength="4000" class="mt-1 w-full rounded-xl border-slate-300" placeholder="Tone, audience, word limit...">{{ old('instructions') }}</textarea></label>
                <button class="w-full rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white hover:bg-indigo-700">Create task</button>
            </form>
            @if($runs->isEmpty())<p class="mt-3 text-sm text-amber-700">No campaign agent runs yet. Create an agent run from a campaign first.</p>@endif
        </section>
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-3">
            <div class="flex items-center justify-between gap-3"><h2 class="text-lg font-bold">Generation queue</h2><a class="text-sm font-semibold text-indigo-600" href="{{ route('agent-center.index') }}">Agent Center</a></div>
            <div class="mt-4 space-y-3">@forelse($tasks as $task)
                <article class="rounded-2xl border border-slate-200 p-4"><div class="flex flex-wrap items-start justify-between gap-2"><div><h3 class="font-semibold">{{ $task->title }}</h3><p class="mt-1 text-xs text-slate-500">{{ $task->run?->campaign?->name }} · {{ data_get($task->input,'platform','generic') }} · #{{ $task->id }}</p></div><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold">{{ ucfirst($task->status) }}</span></div>
                @if($task->error_message)<p class="mt-2 text-xs text-red-700">{{ $task->error_message }}</p>@endif
                @if(in_array($task->status,['ready','failed']))<form method="POST" action="{{ route('agent-tasks.generate-content',$task) }}" class="mt-3">@csrf<button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ $task->status==='failed' ? 'Retry generation' : 'Generate draft' }}</button></form>@endif
                @if($task->status==='completed' && data_get($task->output,'content_asset_id'))<a class="mt-3 inline-block text-sm font-semibold text-indigo-600" href="{{ route('content-library.show',data_get($task->output,'content_asset_id')) }}">View generated draft</a>@endif
                </article>
            @empty<p class="rounded-xl bg-slate-50 p-6 text-sm text-slate-500">No content tasks yet.</p>@endforelse</div><div class="mt-5">{{ $tasks->links() }}</div>
        </section>
    </div>
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex flex-wrap items-center justify-between gap-3"><h2 class="text-xl font-bold">Generated content</h2><a href="{{ route('content-library.index') }}" class="text-sm font-semibold text-indigo-600">Open full library</a></div><div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">@forelse($assets as $asset)<a href="{{ route('content-library.show',$asset) }}" class="rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-400"><div class="flex justify-between gap-2 text-xs"><span class="font-semibold uppercase text-indigo-600">{{ $asset->platform }}</span><span>{{ ucfirst($asset->status) }}</span></div><h3 class="mt-3 font-bold">{{ $asset->title }}</h3><p class="mt-2 line-clamp-4 whitespace-pre-wrap text-sm text-slate-600">{{ $asset->caption }}</p><p class="mt-3 text-xs text-slate-400">{{ $asset->campaign?->name }} · v{{ $asset->version }}</p></a>@empty<p class="text-sm text-slate-500">No generated drafts yet.</p>@endforelse</div><div class="mt-5">{{ $assets->links() }}</div></section>
</div>
</x-app-layout>
