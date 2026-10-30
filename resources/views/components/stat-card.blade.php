@props([
'title',
'value' => 0,
'description' => null,
'icon' => 'sparkles',
])

<div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">

    <div class="flex items-start justify-between gap-4">

        <div>

            <p class="text-sm font-medium text-slate-500">
                {{ $title }}
            </p>

            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                {{ $value }}
            </p>

            @if($description)
                <p class="mt-2 text-xs leading-5 text-slate-400">
                    {{ $description }}
                </p>
            @endif

        </div>

        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">

            @if($icon === 'business')
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 21h18M6 21V5a2 2 0 012-2h8a2 2 0 012 2v16"/>
                </svg>

            @elseif($icon === 'campaign')
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5l8-3v16l-8-3M5 9h6v6H5a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                </svg>

            @elseif($icon === 'token')
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-width="2" d="M9 12h6M12 9v6"/>
                </svg>

            @else
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            @endif

        </div>

    </div>

</div>
