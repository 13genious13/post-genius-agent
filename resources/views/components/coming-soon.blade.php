@props([
'title' => 'Coming Soon',
'description' => 'This module will be available in an upcoming development phase.',
])

<div class="flex min-h-[450px] items-center justify-center">

    <div class="mx-auto max-w-lg text-center">

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-100 to-violet-100 text-indigo-600">

            <svg
                class="h-7 w-7"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 10V3L4 14h7v7l9-11h-7z"
                />
            </svg>

        </div>

        <h1 class="mt-6 text-2xl font-bold tracking-tight text-slate-900">
            {{ $title }}
        </h1>

        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
            {{ $description }}
        </p>

        <a
            href="{{ route('dashboard') }}"
            class="mt-7 inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
        >
            Back to Dashboard
        </a>

    </div>

</div>
