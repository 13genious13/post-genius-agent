<x-app-layout>
<div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6"><div><p class="text-sm font-semibold uppercase tracking-widest text-indigo-600">Administration</p><h1 class="mt-1 text-3xl font-extrabold text-slate-900">System Settings</h1><p class="mt-2 text-base text-slate-600">Workspace preferences only. AI keys, publishing credentials and environment configuration remain server-managed.</p></div>
@if(session('success'))<div class="rounded-xl bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>@endif
@if($errors->any())<div class="rounded-xl bg-rose-50 p-4 text-rose-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.system-settings.update') }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">@csrf @method('PUT')
@foreach($fields as $field)
@php($name=substr($field['key'],strrpos($field['key'],'.')+1))
<div><label for="{{ $name }}" class="mb-2 block text-sm font-semibold text-slate-800">{{ $field['label'] }}</label>
@if($field['type']==='boolean')<input type="hidden" name="{{ $name }}" value="0"><label class="flex items-center gap-3"><input id="{{ $name }}" type="checkbox" name="{{ $name }}" value="1" @checked(old($name,$field['value']) == 1) class="rounded border-slate-300 text-indigo-600"><span class="text-sm text-slate-600">Enable preference</span></label>
@elseif($name==='maintenance_notice')<textarea id="{{ $name }}" name="{{ $name }}" rows="3" maxlength="500" class="w-full rounded-xl border-slate-300 text-base">{{ old($name,$field['value']) }}</textarea>
@else<input id="{{ $name }}" type="{{ $field['type']==='email'?'email':'text' }}" name="{{ $name }}" value="{{ old($name,$field['value']) }}" class="w-full rounded-xl border-slate-300 text-base" @if($name==='company_label'||$name==='default_timezone') required @endif>@endif
</div>@endforeach
<p class="rounded-xl bg-amber-50 p-4 text-sm text-amber-900">These settings are stored and displayed here. They do not automatically reconfigure queue workers, email delivery, the server timezone or publishing kill switches.</p>
<button class="rounded-xl bg-indigo-600 px-6 py-3 text-base font-semibold text-white hover:bg-indigo-700">Save preferences</button></form></div>
</x-app-layout>
