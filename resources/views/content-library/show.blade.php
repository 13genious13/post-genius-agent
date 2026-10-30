<x-app-layout><div class="mx-auto max-w-5xl space-y-6"><div class="flex flex-wrap justify-between gap-4"><div><p class="text-xs font-bold uppercase text-indigo-600">{{ $asset->platform }} · Version {{ $asset->version }}</p><h1 class="mt-2 text-3xl font-bold">{{ $asset->title }}</h1></div><x-status-badge :status="$asset->status" /></div>
@if($asset->status==='draft')
<section class="rounded-3xl border border-indigo-200 bg-indigo-50 p-5"><h2 class="mb-4 font-bold">Edit draft before approval</h2>
<form method="POST" action="{{ route('content-agent.update',$asset) }}" class="space-y-3">
    @csrf @method('PUT')
<label class="block text-sm">Title<input name="title" value="{{ old('title',$asset->title) }}" required maxlength="255" class="mt-1 w-full rounded-xl border-slate-300"></label>
<label class="block text-sm">Hook<textarea name="hook" rows="2" class="mt-1 w-full rounded-xl border-slate-300">{{ old('hook',$asset->hook) }}</textarea></label>
<label class="block text-sm">Caption<textarea name="caption" rows="6" required class="mt-1 w-full rounded-xl border-slate-300">{{ old('caption',$asset->caption) }}</textarea></label>
<label class="block text-sm">Call to action<textarea name="call_to_action" rows="2" class="mt-1 w-full rounded-xl border-slate-300">{{ old('call_to_action',$asset->call_to_action) }}</textarea></label>
<label class="block text-sm">Hashtags<input name="hashtags" value="{{ old('hashtags',implode(' ', $asset->hashtags ?? [])) }}" class="mt-1 w-full rounded-xl border-slate-300"></label>
<button class="rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white">Save draft</button>
</form></section>
        @endif
<div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]"><section class="space-y-5 rounded-3xl border border-slate-200 bg-white p-6"><div><h2 class="font-bold">Hook</h2><p class="mt-2 whitespace-pre-wrap text-slate-600">{{ $asset->hook }}</p></div><div><h2 class="font-bold">Caption</h2><p class="mt-2 whitespace-pre-wrap text-slate-600">{{ $asset->caption }}</p></div><div><h2 class="font-bold">Call to Action</h2><p class="mt-2 text-slate-600">{{ $asset->call_to_action }}</p></div><div><h2 class="font-bold">Hashtags</h2><p class="mt-2 text-indigo-600">{{ implode(' ', $asset->hashtags ?? []) }}</p></div></section>
<section class="space-y-5"><div class="rounded-3xl border border-slate-200 bg-white p-6"><h2 class="font-bold">Creative Brief (Phase 8 input)</h2>@foreach($asset->creative_brief??[] as $key=>$value)<p class="mt-3 text-sm"><strong>{{ str($key)->replace('_',' ')->title() }}:</strong> {{ is_scalar($value) ? $value : json_encode($value) }}</p>@endforeach</div><div class="rounded-3xl border border-slate-200 bg-white p-6"><h2 class="font-bold">Content Variants</h2>@foreach($asset->variants??[] as $variant)<div class="mt-3 border-t pt-3"><p class="text-sm font-semibold">{{ $variant['name']??'Variant' }}</p><p class="mt-1 whitespace-pre-wrap text-sm text-slate-600">{{ $variant['caption']??'' }}</p></div>@endforeach</div>
@if($asset->status==='draft')
        <form method="POST" action="{{ route('content-library.approve',$asset) }}">
            @csrf
            <button class="w-full rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white">Approve Draft</button>
        </form>
        <form method="POST" action="{{ route('content-library.reject',$asset) }}" class="space-y-2">
            @csrf
            <textarea name="reason" required maxlength="1000" placeholder="Reason for rejection" class="w-full rounded-xl border-slate-200"></textarea>
            <button class="w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white">Reject Draft</button>
        </form>
    @endif
    @if(in_array($asset->status, ['draft','approved']))
        <form method="POST" action="{{route('creative-library.generate',$asset)}}">@csrf
            <button class="rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white">Generate Creative</button>
        </form>
    @endif
</section>
</div>
    </div>
</x-app-layout>
