<x-app-layout>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <section>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div>

                    <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                        Developer Workspace
                    </div>

                    <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                        AI Playground
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Test the PostGenius intelligence layer directly with NVIDIA Nemotron
                        through Nebius Token Factory.
                    </p>

                </div>

                <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">

                    <span class="h-2.5 w-2.5 rounded-full {{ config('ai.providers.nebius.api_key') ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>

                    <div>
                        <p class="text-xs font-semibold text-slate-700">
                            Nebius Token Factory
                        </p>

                        <p class="text-[11px] text-slate-400">
                            {{ config('ai.providers.nebius.api_key') ? 'Configured' : 'Configuration required' }}
                        </p>
                    </div>

                </div>

            </div>

        </section>

        @if($errors->has('ai'))

            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ $errors->first('ai') }}
            </div>

        @endif

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">

            {{-- Prompt --}}
            <section class="xl:col-span-3 rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                            Prompt
                        </p>

                        <h2 class="mt-2 text-lg font-bold text-slate-900">
                            Test AI Intelligence
                        </h2>

                    </div>

                    <div class="rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500">
                        Nemotron
                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route('ai.playground.generate') }}"
                    class="mt-6"
                    x-data="{ submitting: false }"
                    @submit="submitting = true"
                >
                    @csrf

                    <label
                        for="prompt"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        What should the AI work on?
                    </label>

                    <textarea
                        id="prompt"
                        name="prompt"
                        rows="12"
                        required
                        maxlength="10000"
                        class="w-full resize-y rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100"
                        placeholder="Example: Create a 7-day social media strategy for a dental clinic in Chandigarh targeting working professionals aged 25-45."
                    >{{ old('prompt') }}</textarea>

                    @error('prompt')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <p class="text-xs leading-5 text-slate-400">
                            Requests are sent securely from Laravel to Nebius.
                            Your API key is never exposed to the browser.
                        </p>

                        <button
                            type="submit"
                            :disabled="submitting"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-600 disabled:cursor-not-allowed disabled:opacity-60"
                        >

                            <svg
                                x-show="!submitting"
                                class="h-4 w-4"
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

                            <svg
                                x-cloak
                                x-show="submitting"
                                class="h-4 w-4 animate-spin"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                ></path>
                            </svg>

                            <span x-text="submitting ? 'Running AI...' : 'Run with Nemotron'">
                                Run with Nemotron
                            </span>

                        </button>

                    </div>

                </form>

            </section>

            {{-- Model information --}}
            <aside class="xl:col-span-2 space-y-6">

                <div class="rounded-[28px] border border-slate-200 bg-slate-950 p-6 text-white shadow-sm">

                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-300">
                        Current Model
                    </p>

                    <h3 class="mt-3 text-lg font-bold">
                        NVIDIA Nemotron
                    </h3>

                    <p class="mt-2 break-all text-xs leading-5 text-slate-400">
                        {{ config('ai.providers.nebius.model') ?: 'Model not configured' }}
                    </p>

                    <div class="mt-6 space-y-4">

                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <span class="text-sm text-slate-400">
                                Provider
                            </span>

                            <span class="text-sm font-semibold">
                                Nebius
                            </span>
                        </div>

                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <span class="text-sm text-slate-400">
                                Temperature
                            </span>

                            <span class="text-sm font-semibold">
                                {{ config('ai.providers.nebius.temperature') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">
                                Max Tokens
                            </span>

                            <span class="text-sm font-semibold">
                                {{ number_format(config('ai.providers.nebius.max_tokens')) }}
                            </span>
                        </div>

                    </div>

                </div>

                <div class="rounded-[28px] border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-6">

                    <p class="text-sm font-bold text-slate-800">
                        Your AI Marketing Assistant
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Turn your ideas into actionable marketing strategies. Ask AI to create campaign plans, generate content ideas, identify target audiences, or improve your marketing approach.
                        Try: “Create a 7-day Instagram strategy for my business.”
                    </p>

                </div>

            </aside>

        </div>

        {{-- AI Response --}}
        @if(session('ai_response'))

            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-600">
                            Successful Response
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-slate-900">
                            AI Output
                        </h2>

                    </div>

                    @if(session('ai_meta'))

                        <div class="flex flex-wrap gap-2">

                            <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                                {{ session('ai_meta.model') ?: 'Model' }}
                            </span>

                            <span class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-600">
                                {{ number_format(session('ai_meta.tokens') ?? 0) }} tokens
                            </span>

                            <span class="rounded-lg bg-violet-50 px-3 py-1.5 text-xs font-medium text-violet-600">
                                {{ number_format(session('ai_meta.latency') ?? 0) }} ms
                            </span>

                        </div>

                    @endif

                </div>

                <div class="p-5 sm:p-7">

                    <div class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">{{ session('ai_response') }}</div>

                </div>

            </section>

        @endif

    </div>

</x-app-layout>
