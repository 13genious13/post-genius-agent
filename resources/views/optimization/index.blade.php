<x-app-layout>
 <x-slot name="header"><h2 class="text-xl font-semibold text-gray-900">Optimization Agent · {{ $campaign->name ?? 'Campaign' }}</h2></x-slot>
 <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
  @if(session('status'))<div class="rounded-xl bg-emerald-50 p-4 text-emerald-800">{{ session('status') }}</div>@endif
  <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
   <h3 class="text-lg font-bold">Evidence-based improvement</h3>
   <p class="text-sm text-gray-600 mt-2">Analyze the latest comparable published-post metrics and Phase 10 marketing memories. Suggestions require approval. This feature never publishes or changes campaign settings.</p>
   <form method="POST" action="{{ route('optimization.store',[$business,$campaign]) }}" class="mt-5">@csrf
    <button class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 font-semibold">Analyze campaign performance</button>
   </form>
  </div>
  <div class="rounded-2xl bg-white shadow-sm overflow-hidden"><div class="p-5 font-semibold">Analysis history</div>
   @forelse($runs as $run)
    <a href="{{ route('optimization.show',[$business,$campaign,$run]) }}" class="flex justify-between items-center border-t p-5 hover:bg-gray-50">
     <span>Run #{{ $run->id }} · {{ $run->created_at->format('d M Y H:i') }}</span><span class="text-sm rounded-full bg-gray-100 px-3 py-1">{{ str_replace('_',' ',ucfirst($run->status)) }}</span>
    </a>
   @empty <p class="p-5 text-gray-500">No optimization runs yet.</p> @endforelse
  </div>{{ $runs->links() }}
 </div>
</x-app-layout>
