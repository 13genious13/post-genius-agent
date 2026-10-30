@extends('layouts.app')
@section('content')
<div class="space-y-7">
 <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
  <div><p class="text-sm font-semibold text-indigo-600">Agent operations</p><h1 class="text-3xl font-bold tracking-tight text-slate-900">Activity Monitor</h1><p class="mt-2 text-base text-slate-500">Explore agent events, warnings and failures across your accessible businesses.</p></div>
  <a href="{{ route('activity.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">Refresh activity</a>
 </div>
 <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
 @foreach(['info'=>['Information','indigo'],'success'=>['Success','emerald'],'warning'=>['Warnings','amber'],'error'=>['Errors','rose']] as $key=>$meta)
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">{{ $meta[0] }}</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format((int)($stats[$key] ?? 0)) }}</p></div>
 @endforeach
 </div>
 <form method="GET" class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-5">
  <select name="business_id" aria-label="Business" class="rounded-xl border-slate-200 text-sm"><option value="">All accessible businesses</option>@foreach($businesses as $business)<option value="{{ $business->id }}" @selected($selectedId===$business->id)>{{ $business->name }}</option>@endforeach</select>
  <select name="level" aria-label="Level" class="rounded-xl border-slate-200 text-sm"><option value="">All levels</option>@foreach(['info','success','warning','error'] as $level)<option value="{{ $level }}" @selected(($filters['level'] ?? '')===$level)>{{ ucfirst($level) }}</option>@endforeach</select>
  <select name="agent" aria-label="Agent" class="rounded-xl border-slate-200 text-sm"><option value="">All agents</option>@foreach($agents as $agent)<option value="{{ $agent }}" @selected(($filters['agent'] ?? '')===$agent)>{{ $agent }}</option>@endforeach</select>
  <select name="days" aria-label="Period" class="rounded-xl border-slate-200 text-sm">@foreach([7,30,90] as $period)<option value="{{ $period }}" @selected($days===$period)>Last {{ $period }} days</option>@endforeach</select>
  <div class="flex gap-2"><input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search events" class="min-w-0 w-full rounded-xl border-slate-200 text-sm"/><button class="rounded-xl bg-indigo-600 px-4 py-2 font-semibold text-white">Filter</button></div>
 </form>
 <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 px-5 py-4 text-lg font-semibold">Event timeline</div>
  <div class="divide-y divide-slate-100">
  @forelse($activities as $activity)
   <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0"><div class="flex flex-wrap items-center gap-2"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $activity->level === 'error' ? 'bg-rose-100 text-rose-700' : ($activity->level === 'warning' ? 'bg-amber-100 text-amber-700' : ($activity->level === 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700')) }}">{{ strtoupper($activity->level) }}</span><span class="text-xs text-slate-500">{{ $activity->agent_key ?? 'System' }}</span></div><h2 class="mt-2 font-semibold text-slate-900">{{ $activity->title }}</h2><p class="mt-1 break-words text-sm text-slate-600">{{ $activity->message }}</p><p class="mt-2 text-xs text-slate-400">{{ $activity->run?->business?->name ?? 'Unknown business' }} · {{ $activity->created_at?->format('d M Y, H:i') }}</p></div>
    <a href="{{ route('activity.runs.show', $activity->agent_run_id) }}" class="shrink-0 text-sm font-semibold text-indigo-600 hover:underline">View run</a>
   </div>
  @empty <p class="p-10 text-center text-slate-500">No agent events match these filters.</p> @endforelse
  </div>
 </div>
 {{ $activities->links() }}
</div>
@endsection
