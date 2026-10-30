<x-app-layout>

    <div class="space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Target Audiences
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Define the customer personas the future agents should understand.
            </p>
        </div>

        <x-business-tabs :business="$business" />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">

            <form
                method="POST"
                action="{{ route('businesses.audiences.store', $business) }}"
                class="xl:col-span-2 space-y-4 rounded-[28px] border border-slate-200 bg-white p-6"
            >
                @csrf

                <input
                    name="name"
                    required
                    placeholder="Persona name"
                    class="w-full rounded-xl border-slate-200"
                >

                <select
                    name="type"
                    class="w-full rounded-xl border-slate-200"
                >
                    <option value="customer">Customer</option>
                    <option value="buyer">Buyer</option>
                    <option value="decision_maker">Decision Maker</option>
                    <option value="influencer">Influencer</option>
                </select>

                <div class="grid grid-cols-2 gap-3">

                    <input
                        name="age_range"
                        placeholder="Age e.g. 25-45"
                        class="rounded-xl border-slate-200"
                    >

                    <input
                        name="gender"
                        placeholder="Gender"
                        class="rounded-xl border-slate-200"
                    >

                </div>

                <textarea
                    name="description"
                    rows="3"
                    placeholder="Persona description"
                    class="w-full rounded-xl border-slate-200"
                ></textarea>

                @foreach([
                'locations_text' => 'Locations',
                'interests_text' => 'Interests',
                'pain_points_text' => 'Pain Points',
                'goals_text' => 'Goals',
                'objections_text' => 'Objections',
                'buying_triggers_text' => 'Buying Triggers',
                'preferred_channels_text' => 'Preferred Channels',
                ] as $field => $placeholder)

                    <textarea
                        name="{{ $field }}"
                        rows="2"
                        placeholder="{{ $placeholder }} — comma or new line separated"
                        class="w-full rounded-xl border-slate-200"
                    ></textarea>

                @endforeach

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input
                        type="checkbox"
                        name="is_primary"
                        value="1"
                        class="rounded border-slate-300"
                    >
                    Primary audience
                </label>

                <button class="w-full rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white">
                    Add Audience
                </button>

            </form>

            <section class="xl:col-span-3 space-y-4">

                @forelse($audiences as $audience)

                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">

                        <div class="flex justify-between gap-5">

                            <div>

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="font-bold text-slate-900">
                                        {{ $audience->name }}
                                    </h3>

                                    @if($audience->is_primary)
                                        <span class="rounded-full bg-indigo-50 px-2 py-1 text-[10px] font-bold uppercase text-indigo-600">
 Primary
 </span>
                                    @endif

                                </div>

                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $audience->description }}
                                </p>

                                @if($audience->pain_points)
                                    <div class="mt-4 flex flex-wrap gap-2">

                                        @foreach(array_slice($audience->pain_points, 0, 5) as $point)

                                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs text-slate-600">
 {{ $point }}
 </span>

                                        @endforeach

                                    </div>
                                @endif

                            </div>

                            <form
                                method="POST"
                                action="{{ route('businesses.audiences.destroy', [$business, $audience]) }}"
                                onsubmit="return confirm('Remove this audience?')"
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
                        No target audiences added yet.
                    </div>

                @endforelse

            </section>

        </div>

    </div>

</x-app-layout>
