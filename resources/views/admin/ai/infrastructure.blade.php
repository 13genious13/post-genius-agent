<x-app-layout>
<div class="mx-auto max-w-7xl space-y-6 px-3 py-6 sm:px-6">
  <div><h1 class="text-2xl font-bold text-slate-950">AI Infrastructure</h1><p class="mt-1 text-sm text-slate-500">Provider status, usage and recent requests.</p></div>
  @if(session('success'))<div class="rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="rounded-xl bg-red-50 p-3 text-sm text-red-800">Provider connection test failed. Check server logs.</div>@endif
  <section class="grid grid-cols-2 gap-3 lg:grid-cols-5">
    @foreach(['requests'=>'Requests','successful'=>'Successful','failed'=>'Failed','tokens'=>'Tokens','average_latency'=>'Avg latency (ms)'] as $key=>$label)
      <div class="rounded-2xl border border-slate-200 bg-white p-4"><p class="text-xs text-slate-500">{{ $label }}</p><p class="mt-2 text-xl font-bold text-slate-900">{{ number_format((float)($stats[$key] ?? 0), $key==='average_latency'?0:0) }}</p></div>
    @endforeach
  </section>
  <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-6"><h2 class="mb-4 font-semibold">Providers</h2><div class="grid gap-3 lg:grid-cols-2">
    @forelse($providers as $provider)
      <div class="min-w-0 rounded-xl border border-slate-200 p-4"><div class="flex flex-wrap items-center justify-between gap-2"><h3 class="font-semibold text-slate-900">{{ $provider->name }}</h3><span class="rounded-full px-2 py-1 text-xs {{ $provider->is_active ? 'bg-emerald-50 text-emerald-700':'bg-slate-100 text-slate-600' }}">{{ $provider->is_active ? 'Enabled':'Disabled' }}</span></div>
      <p class="mt-2 break-all text-xs text-slate-500">{{ $provider->key }} · {{ $provider->default_model ?? 'Model not set' }}</p>
      <p class="mt-2 text-xs text-slate-500">Last test: {{ $provider->last_tested_at?->format('d M Y H:i') ?? 'Never' }} · {{ $provider->last_test_success === null ? 'Not tested' : ($provider->last_test_success ? 'Passed':'Failed') }}</p>
      <form method="POST" action="{{ route('admin.ai.infrastructure.test', $provider->key) }}" class="mt-3">@csrf<button class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white">Test connection</button></form></div>
    @empty <p class="text-sm text-slate-500">No AI providers configured.</p>@endforelse
  </div></section>
  <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-6"><h2 class="mb-4 font-semibold">Recent AI requests</h2><div class="overflow-x-auto"><table class="min-w-full text-left text-xs"><thead class="bg-slate-50 text-slate-500"><tr>@foreach(['Date','Feature','Provider','Model','Status','Tokens','Latency'] as $heading)<th class="whitespace-nowrap px-3 py-3">{{ $heading }}</th>@endforeach</tr></thead><tbody class="divide-y divide-slate-100">@forelse($logs as $log)<tr><td class="whitespace-nowrap px-3 py-3">{{ $log->created_at?->format('d M H:i') }}</td><td class="px-3 py-3">{{ $log->feature }}</td><td class="px-3 py-3">{{ $log->provider }}</td><td class="max-w-48 break-words px-3 py-3">{{ $log->model }}</td><td class="px-3 py-3">{{ $log->status }}</td><td class="px-3 py-3">{{ $log->total_tokens ?? 0 }}</td><td class="px-3 py-3">{{ $log->latency_ms ?? '—' }}</td></tr>@empty<tr><td colspan="7" class="px-3 py-4 text-slate-500">No requests recorded.</td></tr>@endforelse</tbody></table></div></section>
</div>
</x-app-layout>
