<x-app-layout>

    <div class="space-y-7">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    Workspace
                </p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                    Businesses
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Manage the businesses PostGenius Agent will understand and market.
                </p>
            </div>

            <a
                href="{{ route('businesses.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-600"
            >
                + Add Business
            </a>

        </div>

        @if($businesses->isEmpty())

            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <span class="text-2xl font-bold">+</span>
                </div>

                <h2 class="mt-5 text-xl font-bold text-slate-900">
                    Create your first business
                </h2>

                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                    Business information becomes the knowledge foundation used by future marketing agents.
                </p>

                <a
                    href="{{ route('businesses.create') }}"
                    class="mt-6 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Create Business
                </a>

            </div>

        @else

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                @foreach($businesses as $business)

                    <a
                        href="{{ route('businesses.show', $business) }}"
                        class="group rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg"
                    >

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-lg font-bold text-white">
                                {{ strtoupper(substr($business->name, 0, 1)) }}
                            </div>

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
 {{ $business->profile_completion }}%
 </span>

                        </div>

                        <h2 class="mt-5 text-lg font-bold text-slate-900 group-hover:text-indigo-600">
                            {{ $business->name }}
                        </h2>

                        <p class="mt-1 text-sm font-medium text-slate-400">
                            {{ $business->industry }}
                        </p>

                        <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-500">
                            {{ $business->description }}
                        </p>

                        <div class="mt-6">

                            <div class="mb-2 flex justify-between text-xs">
 <span class="font-medium text-slate-500">
 Profile completion
 </span>

                                <span class="font-bold text-slate-700">
 {{ $business->profile_completion }}%
 </span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full bg-indigo-600"
                                    style="width: {{ min(100, $business->profile_completion) }}%"
                                ></div>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

            <div>
                {{ $businesses->links() }}
            </div>

        @endif

    </div>

</x-app-layout>
