<x-app-layout>
 <x-slot name="header"><h1 class="text-2xl font-bold text-slate-900">Agent Governance · {{ $business->name }}</h1></x-slot>
 <div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
  @if(session('status'))<div class="rounded-xl bg-emerald-50 p-4 text-emerald-800">{{ session('status') }}</div>@endif
  @if($errors->any())<div class="rounded-xl bg-red-50 p-4 text-red-800">{{ $errors->first() }}</div>@endif
  <div class="grid gap-4 md:grid-cols-3">
   <div class="rounded-2xl bg-white p-5 shadow"><p class="text-sm text-slate-500">Autonomy mode</p><p class="text-xl font-semibold">{{ ucfirst($control?->autonomy_mode ?? 'manual') }}</p></div>
   <div class="rounded-2xl bg-white p-5 shadow"><p class="text-sm text-slate-500">Pending approvals</p><p class="text-xl font-semibold">{{ $requests->getCollection()->where('status','pending')->count() }} shown</p></div>
   <div class="rounded-2xl bg-white p-5 shadow"><p class="text-sm text-slate-500">Emergency pause</p><p class="text-xl font-semibold {{ $limits->pause_all_agents ? 'text-red-700':'text-emerald-700' }}">{{ $limits->pause_all_agents ? 'ON':'OFF' }}</p></div>
  </div>
  <section class="rounded-2xl bg-white p-6 shadow space-y-5"><h2 class="text-xl font-semibold">Safety controls</h2>
   <form method="POST" action="{{ route('agent-governance.update',$business) }}" class="grid gap-4 md:grid-cols-2">@csrf @method('PUT')
    <label class="block text-sm">Daily request limit<input class="mt-1 w-full rounded-lg border-slate-300" type="number" name="max_daily_requests" min="1" max="100" value="{{ old('max_daily_requests',$limits->max_daily_requests) }}" required></label>
    <label class="block text-sm">Pending approval limit<input class="mt-1 w-full rounded-lg border-slate-300" type="number" name="max_pending_approvals" min="1" max="100" value="{{ old('max_pending_approvals',$limits->max_pending_approvals) }}" required></label>
    <label class="flex gap-2 items-center"><input type="checkbox" name="pause_all_agents" value="1" @checked(old('pause_all_agents',$limits->pause_all_agents))> Pause all agent actions</label>
    <label class="flex gap-2 items-center"><input type="checkbox" name="require_two_person_review" value="1" @checked(old('require_two_person_review',$limits->require_two_person_review))> Require separate reviewer</label>
    <fieldset class="md:col-span-2"><legend class="text-sm font-medium">Block specific actions</legend><div class="grid md:grid-cols-3 gap-2 mt-2">
     @foreach(\App\AgentGovernance\ActionCatalog::ACTIONS as $key=>$action)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="blocked_action_keys[]" value="{{ $key }}" @checked(in_array($key,old('blocked_action_keys',$limits->blocked_action_keys??[])))> {{ $action['label'] }}</label>@endforeach
    </div></fieldset><button class="md:col-span-2 rounded-xl bg-indigo-700 px-5 py-3 text-white font-semibold">Save safety controls</button>
   </form>
  </section>
  <section class="rounded-2xl bg-white p-6 shadow space-y-4"><h2 class="text-xl font-semibold">Request approval (governance-only)</h2><p class="text-sm text-slate-600">This creates an auditable approval request; it does not execute the selected action.</p>
   <form method="POST" action="{{ route('agent-governance.requests.store',$business) }}" class="grid md:grid-cols-3 gap-3">@csrf
    <select name="action_key" required class="rounded-lg border-slate-300">@foreach(\App\AgentGovernance\ActionCatalog::ACTIONS as $key=>$action)<option value="{{ $key }}">{{ $action['label'] }}</option>@endforeach</select>
    <input name="idempotency_key" class="rounded-lg border-slate-300" placeholder="Unique request reference (8+ chars)" minlength="8" maxlength="100" required>
    <input name="reason" class="rounded-lg border-slate-300" placeholder="Business reason (optional)" maxlength="1000">
    <button class="rounded-xl bg-indigo-700 px-5 py-3 text-white font-semibold">Create approval request</button>
   </form>
  </section>
  <section class="rounded-2xl bg-white p-6 shadow space-y-4"><h2 class="text-xl font-semibold">Approval inbox</h2><div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="p-3">Request</th><th class="p-3">Action</th><th class="p-3">Status</th><th class="p-3">Decision</th></tr></thead><tbody>
   @forelse($requests as $item)<tr class="border-b"><td class="p-3">#{{ $item->id }}<div class="text-slate-500">{{ $item->created_at->diffForHumans() }}</div></td><td class="p-3">{{ $item->action_key }}</td><td class="p-3">{{ $item->status }}</td><td class="p-3">@if($item->status==='pending')<form method="POST" action="{{ route('agent-governance.requests.decide',[$business,$item]) }}" class="flex flex-wrap gap-2">@csrf<input name="note" class="rounded-lg border-slate-300 text-xs" placeholder="Optional note"><button name="decision" value="approved" class="rounded-lg bg-emerald-700 px-3 py-2 text-white">Approve</button><button name="decision" value="rejected" class="rounded-lg bg-red-700 px-3 py-2 text-white">Reject</button></form>@else{{ $item->reviewer?->name ?? '—' }}@endif</td></tr>
   @empty<tr><td colspan="4" class="p-4 text-slate-500">No requests yet.</td></tr>@endforelse
   </tbody></table></div>{{ $requests->links() }}</section>
  <section class="rounded-2xl bg-white p-6 shadow"><h2 class="text-xl font-semibold mb-4">Recent governance activity</h2><div class="space-y-3">@forelse($events as $event)<div class="border-b pb-2 text-sm"><strong>{{ $event->event_key }}</strong> <span class="text-slate-500">{{ $event->created_at->format('d M Y H:i') }}</span></div>@empty<p class="text-slate-500">No activity yet.</p>@endforelse</div></section>
 </div>
</x-app-layout>
