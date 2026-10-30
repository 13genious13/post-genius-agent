<x-app-layout>

    <div class="space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Competitors
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Add known competitors. Live competitive research will be introduced later.
            </p>
        </div>

        <x-business-tabs :business="$business" />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">

            <form
                method="POST"
                action="{{ route('businesses.competitors.store', $business) }}"
                class="xl:col-span-2 space-y-4 rounded-[28px] border border-slate-200 bg-white p-6"
            >
                @csrf

                <input
                    name="name"
                    required
                    placeholder="Competitor name"
                    class="w-full rounded-xl border-slate-200"
                >

                <input
                    type="url"
                    name="website"
                    placeholder="https://competitor.com"
                    class="w-full rounded-xl border-slate-200"
                >

                <textarea
                    name="notes"
                    rows="5"
                    placeholder="What do you already know about this competitor?"
                    class="w-full rounded-xl border-slate-200"
                ></textarea>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input
                        type="checkbox"
                        name="is_primary"
                        value="1"
                        class="rounded"
                    >
                    Primary competitor
                </label>

                <button class="w-full rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white">
                    Add Competitor
                </button>

            </form>

            <section class="xl:col-span-3 space-y-4">

                @forelse($competitors as $competitor)

                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">

                        <div class="flex items-start justify-between gap-5">

                            <div>

                                <div class="flex items-center gap-2">

                                    <h3 class="font-bold">
                                        {{ $competitor->name }}
                                    </h3>

                                    @if($competitor->is_primary)
                                        <span class="rounded-full bg-indigo-50 px-2 py-1 text-[10px] font-bold uppercase text-indigo-600">
 Primary
 </span>
                                    @endif

                                </div>

                                @if($competitor->website)

                                    <a
                                        href="{{ $competitor->website }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-1 block text-sm text-indigo-600"
                                    >
                                        {{ $competitor->website }}
                                    </a>

                                @endif

                                <p class="mt-3 text-sm leading-6 text-slate-500">
                                    {{ $competitor->notes }}
                                </p>

                            </div>

                            <form
                                method="POST"
                                action="{{ route('businesses.competitors.destroy', [$business, $competitor]) }}"
                                onsubmit="return confirm('Remove competitor?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="text-sm font-semibold text-red-500">
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                @empty

                    <div class="rounded-[28px] border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-400">
                        No competitors added.
                    </div>

                @endforelse

            </section>

        </div>

    </div>

</x-app-layout>
