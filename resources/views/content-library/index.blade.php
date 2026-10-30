<x-app-layout>
<div class="space-y-6"><div><p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Content Agent</p><h1 class="mt-2 text-3xl font-bold">Content Library</h1><p class="mt-2 text-sm text-slate-500">AI-generated drafts. No content is published from this screen.</p></div>
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">@forelse($assets as $asset)
<a href="{{ route('content-library.show',$asset) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-indigo-300"><div class="flex justify-between gap-2"><span class="text-xs font-bold uppercase text-indigo-600">{{ $asset->platform }}</span><x-status-badge :status="$asset->status" /></div><h2 class="mt-3 font-bold">{{ $asset->title }}</h2><p class="mt-2 line-clamp-4 text-sm text-slate-600">{{ $asset->caption }}</p><p class="mt-4 text-xs text-slate-400">{{ $asset->business->name }} · {{ $asset->campaign->name }} · v{{ $asset->version }}</p></a>
@empty<div class="rounded-3xl border border-dashed border-slate-300 p-10 text-slate-500">No drafts yet. Generate content from an Agent Run task.</div>@endforelse</div>{{ $assets->links() }}</div>
</x-app-layout>
