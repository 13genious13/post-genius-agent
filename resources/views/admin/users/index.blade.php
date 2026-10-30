<x-app-layout>
<div class="mx-auto max-w-7xl space-y-6 px-3 py-6 sm:px-6">
    <div><h1 class="text-2xl font-bold text-slate-950">Users & Team Management</h1><p class="mt-1 text-sm text-slate-500">Platform administrator accounts. Business-specific membership is managed separately.</p></div>
    @if(session('success'))<div class="rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="rounded-xl bg-red-50 p-3 text-sm text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-6">
      <h2 class="mb-4 font-semibold">Create user</h2>
      <form method="POST" action="{{ route('admin.users.store') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">@csrf
        <input required name="name" value="{{ old('name') }}" placeholder="Full name" class="rounded-xl border-slate-300 text-sm" />
        <input required type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="rounded-xl border-slate-300 text-sm" />
        <input required type="password" name="password" placeholder="Password" autocomplete="new-password" class="rounded-xl border-slate-300 text-sm" />
        <input required type="password" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" class="rounded-xl border-slate-300 text-sm" />
        <div class="flex gap-2"><select name="role" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm"><option value="user">User</option><option value="admin">Admin</option></select><button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Add</button></div>
      </form>
    </section>
    <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-6">
      <form method="GET" class="mb-4 flex flex-col gap-2 sm:flex-row"><input name="q" value="{{ request('q') }}" placeholder="Search users" class="w-full rounded-xl border-slate-300 text-sm sm:max-w-sm"/><button class="rounded-xl bg-slate-900 px-5 py-2 text-sm text-white">Search</button></form>
      <div class="space-y-3">@forelse($users as $user)
        <div class="rounded-xl border border-slate-200 p-3 sm:p-4">
          <form method="POST" action="{{ route('admin.users.update', $user) }}" class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">@csrf @method('PATCH')
            <input aria-label="Name" name="name" value="{{ $user->name }}" required class="min-w-0 rounded-lg border-slate-300 text-sm" />
            <input aria-label="Email" name="email" type="email" value="{{ $user->email }}" required class="min-w-0 rounded-lg border-slate-300 text-sm" />
            <select aria-label="Role" name="role" class="rounded-lg border-slate-300 text-sm"><option value="user" @selected($user->role==='user')>User</option><option value="admin" @selected($user->role==='admin')>Admin</option></select>
            <select aria-label="Status" name="is_active" class="rounded-lg border-slate-300 text-sm"><option value="1" @selected($user->is_active)>Active</option><option value="0" @selected(!$user->is_active)>Inactive</option></select>
            <div class="self-center text-xs text-slate-500">{{ $user->businesses_count }} businesses<br/>Last login: {{ $user->last_login_at?->format('d M Y') ?? 'Never' }}</div>
            <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Save changes</button>
          </form>
          <details class="mt-3"><summary class="cursor-pointer text-xs font-semibold text-indigo-700">Set new password</summary><form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="mt-3 flex flex-col gap-2 sm:flex-row">@csrf<input required type="password" name="password" autocomplete="new-password" placeholder="New password" class="rounded-lg border-slate-300 text-sm"/><input required type="password" name="password_confirmation" autocomplete="new-password" placeholder="Confirm password" class="rounded-lg border-slate-300 text-sm"/><button class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">Update password</button></form></details>
        </div>
      @empty <p class="text-sm text-slate-500">No users found.</p>@endforelse</div>
      <div class="mt-5">{{ $users->links() }}</div>
    </section>
</div>
</x-app-layout>
