@props([
'status' => 'ready',
])




@php
    $status=strtolower($status);




    $config=match($status) {




    'success',
    'connected',
    'ready',
    'active',
    'completed',
    'generated' => [
    'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
    'dot' => 'bg-emerald-500',
    ],




    'failed',
    'error',
    'inactive',
    'cancelled' => [
    'class' => 'border-red-200 bg-red-50 text-red-700',
    'dot' => 'bg-red-500',
    ],




    'pending',
    'processing',
    'planning' => [
    'class' => 'border-amber-200 bg-amber-50 text-amber-700',
    'dot' => 'bg-amber-500',
    ],




    'paused' => [
    'class' => 'border-orange-200 bg-orange-50 text-orange-700',
    'dot' => 'bg-orange-500',
    ],




    'draft' => [
    'class' => 'border-slate-200 bg-slate-50 text-slate-600',
    'dot' => 'bg-slate-400',
    ],




    default => [
    'class' => 'border-slate-200 bg-slate-50 text-slate-600',
    'dot' => 'bg-slate-400',
    ],
    };
@endphp




<span
{{ $attributes->merge([
'class' =>
'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold '
. $config['class']
]) }}
>
<span class="h-1.5 w-1.5 rounded-full {{ $config['dot'] }}"></span>




{{ str($status)->replace('_', ' ')->title() }}
</span>
