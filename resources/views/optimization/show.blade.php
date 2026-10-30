<x-app-layout>
 <x-slot name="header"><h2 class="text-xl font-semibold">Optimization Run #{{ $optimizationRun->id }}</h2></x-slot>
 <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
  @if(session('status'))<div class="rounded-xl bg-emerald-50 text-emerald-900 p-4">{{ session('status') }}</div>@endif
  @if($errors->any())<div class="rounded-xl bg-red-50 text-red-800 p-4">{{ $errors->first() }}</div>@endif
  <a class="text-indigo-600 hover:underline" href="{{ route('optimization.index',[$business,$campaign]) }}">← All runs</a>
  <section class="rounded-2xl bg-white border p-6"><div class="flex flex-wrap justify-between gap-3"><h3 class="font-bold text-lg">Analysis status: {{ ucfirst(str_replace('_',' ',$optimizationRun->status)) }}</h3><span class="text-gray-500">Comparable posts: {{ $optimizationRun->eligible_posts }}</span></div>
   <p class="mt-3 text-gray-600">{{ data_get($optimizationRun->summary,'message','The analysis is processing.') }}</p>
   @if(in_array($optimizationRun->status,['pending','running']))<p class="text-sm text-gray-500 mt-2">Refresh this page to see results.</p>@endif
  </section>
  @foreach($optimizationRun->recommendations as $rec)
   <article class="rounded-2xl bg-white border p-6 space-y-4">
    <div class="flex flex-wrap justify-between gap-2"><h3 class="font-bold text-lg">{{ $rec->title }}</h3><span class="text-sm bg-indigo-50 text-indigo-800 rounded-full px-3 py-1">{{ ucfirst($rec->status) }} · {{ ucfirst($rec->priority) }}</span></div>
    <div><strong>Evidence and reasoning</strong><p class="text-gray-700 whitespace-pre-wrap">{{ $rec->rationale }}</p></div>
    <div><strong>Proposed change</strong><p class="text-gray-700 whitespace-pre-wrap">{{ $rec->proposed_change }}</p></div>
    <p class="text-xs text-gray-500">Supporting publication IDs: {{ implode(', ', $rec->evidence_publication_ids ?? []) }} · Confidence: {{ $rec->confidence }}. Observational evidence is not proof of causation.</p>
    @if($rec->status==='proposed')
     <form method="POST" action="{{ route('optimization.review',[$business,$campaign,$rec]) }}" class="space-y-3">@csrf
      <label class="block text-sm">Review note <textarea name="note" maxlength="2000" rows="2" class="w-full rounded-xl border-gray-300"></textarea></label>
      <div class="flex gap-3"><button name="decision" value="approved" class="bg-emerald-600 text-white px-4 py-2 rounded-xl">Approve suggestion</button><button name="decision" value="rejected" class="bg-gray-700 text-white px-4 py-2 rounded-xl">Reject</button></div>
     </form>
    @elseif($rec->status==='approved' && !$rec->experiment)
     <form method="POST" action="{{ route('optimization.experiment',[$business,$campaign,$rec]) }}" class="space-y-3 border-t pt-4">@csrf
      <h4 class="font-semibold">Create a draft experiment (manual execution)</h4>
      <input name="hypothesis" required maxlength="255" placeholder="Test hypothesis" class="w-full rounded-xl border-gray-300" />
      <textarea name="test_plan" required rows="3" maxlength="5000" placeholder="Control, variant, duration and guardrails" class="w-full rounded-xl border-gray-300"></textarea>
      <div class="flex gap-3"><select name="success_metric" class="rounded-xl border-gray-300"><option value="interaction_rate">Interaction rate</option><option value="clicks">Clicks</option><option value="reach">Reach</option></select><input name="target" type="number" step="any" min="0" placeholder="Optional target" class="rounded-xl border-gray-300" /></div>
      <button class="bg-indigo-600 text-white px-4 py-2 rounded-xl">Save experiment plan</button>
     </form>
    @elseif($rec->experiment)<div class="bg-amber-50 p-4 rounded-xl text-sm">Experiment #{{ $rec->experiment->id }} · {{ ucfirst($rec->experiment->status) }}. Run manually and record results in a future phase.</div>@endif
   </article>
  @endforeach
  @if($optimizationRun->status==='completed' && $optimizationRun->recommendations->isEmpty())<div class="p-5 rounded-xl bg-amber-50">No recommendations met the evidence validation rules.</div>@endif
 </div>
</x-app-layout>
