<x-app-layout>
<div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div><p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">AI Creative Studio</p><h1 class="mt-1 text-3xl font-bold text-slate-900">Creative Agent</h1><p class="mt-2 max-w-2xl text-sm text-slate-600">Turn campaign drafts into image concepts and visual assets. Every generated creative goes through a brand-specification review and human approval.</p></div>
        <a href="{{ route('creative-library.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Open Creative Library</a>
    </div>
    @if(session('success'))<div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach(['total'=>'Total creatives','in_progress'=>'Generating','review'=>'Needs review','approved'=>'Approved'] as $key=>$label)
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats[$key]) }}</p></div>
        @endforeach
    </div>
    <div class="grid gap-6 lg:grid-cols-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-bold text-slate-900">Generate a creative</h2><p class="mt-1 text-sm text-slate-500">Choose a draft or approved Content Agent asset. Image generation uses your configured provider.</p>
            <form method="GET" action="{{ route('creative-agent.index') }}" class="mt-5"><label for="business_id" class="mb-1 block text-sm font-medium text-slate-700">Filter business</label><select id="business_id" name="business_id" onchange="this.form.submit()" class="w-full rounded-xl border-slate-300 text-sm"><option value="">All my businesses</option>@foreach($businesses as $business)<option value="{{ $business->id }}" @selected($selectedBusiness===$business->id)>{{ $business->name }}</option>@endforeach</select></form>
            <form method="POST" action="{{ route('creative-agent.generate') }}" class="mt-4 space-y-4">@csrf
                <div><label for="content_asset_id" class="mb-1 block text-sm font-medium text-slate-700">Content asset</label><select id="content_asset_id" name="content_asset_id" required class="w-full rounded-xl border-slate-300 text-sm"><option value="">Select campaign content</option>@foreach($contents as $content)<option value="{{ $content->id }}" @selected(old('content_asset_id')==$content->id)>#{{ $content->id }} · {{ $content->business?->name }} · {{ \Illuminate\Support\Str::limit($content->title ?: $content->caption ?: 'Untitled draft', 45) }} ({{ $content->status }})</option>@endforeach</select></div>
                <p class="rounded-xl bg-indigo-50 p-3 text-xs leading-5 text-indigo-800">A new version is created, queued on the <strong>creative</strong> worker, reviewed by Brand Guardian, then held for manual approval. This action does not publish content.</p>
                <button type="submit" @disabled($contents->isEmpty()) class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">Generate AI Creative</button>
            </form>
            @if($contents->isEmpty())<p class="mt-3 text-sm text-amber-700">No eligible drafts found. <a class="font-semibold underline" href="{{ route('content-library.index') }}">Open Content Library</a> to create or review content first.</p>@endif
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-3">
            <div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-lg font-bold text-slate-900">Recent creatives</h2><span class="text-xs text-slate-500">Private to your businesses</span></div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @forelse($assets as $asset)
                    <a href="{{ route('creative-library.show',$asset) }}" class="group overflow-hidden rounded-xl border border-slate-200 transition hover:border-indigo-300 hover:shadow-md">
                        <div class="flex aspect-video items-center justify-center bg-slate-100">@if($asset->storage_path)<img loading="lazy" class="h-full w-full object-contain" src="{{ route('creative-library.image',$asset) }}" alt="{{ data_get($asset->design_spec,'accessibility_alt','Campaign creative') }}">@else<span class="text-sm text-slate-500">Image pending</span>@endif</div>
                        <div class="p-3"><p class="truncate text-sm font-semibold text-slate-900">{{ $asset->contentAsset?->title ?: 'Campaign Creative' }}</p><p class="mt-1 text-xs text-slate-500">{{ str_replace('_',' ',ucfirst($asset->status)) }} · v{{ $asset->version }} · {{ ucfirst($asset->platform) }}</p><p class="mt-1 text-xs text-slate-500">Brand score: {{ $asset->brand_score===null ? 'Pending' : $asset->brand_score.'/100' }}</p></div>
                    </a>
                @empty<div class="col-span-2 rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">No creatives generated yet.</div>@endforelse
            </div>
            <div class="mt-4">{{ $assets->links() }}</div>
        </section>
    </div>
    <p class="text-xs text-slate-500">Brand Guardian evaluates the written design specification and marketing copy; it cannot verify the generated image's actual pixels, logo fidelity or embedded text. Review each image manually before approval.</p>
</div>
</x-app-layout>
