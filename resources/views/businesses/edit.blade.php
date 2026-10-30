<x-app-layout>

    <div class="space-y-6">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                Business Profile
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-950">
                {{ $business->name }}
            </h1>
        </div>

        <x-business-tabs :business="$business" />

        <form
            method="POST"
            action="{{ route('businesses.update', $business) }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                @foreach([
                'name' => 'Business Name',
                'tagline' => 'Tagline',
                'industry' => 'Industry',
                'business_type' => 'Business Type',
                'website' => 'Website',
                'country' => 'Country',
                'state' => 'State',
                'city' => 'City',
                'timezone' => 'Timezone',
                'primary_language' => 'Primary Language',
                ] as $field => $label)

                    <div>

                        <label class="text-sm font-semibold text-slate-700">
                            {{ $label }}
                        </label>

                        <input
                            type="{{ $field === 'website' ? 'url' : 'text' }}"
                            name="{{ $field }}"
                            value="{{ old($field, $business->{$field}) }}"
                            class="mt-2 w-full rounded-xl border-slate-200"
                        >

                        @error($field)
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                @endforeach

                @foreach([
                'description' => 'Business Description',
                'mission' => 'Mission',
                'vision' => 'Vision',
                'unique_value_proposition' => 'Unique Value Proposition',
                'brand_story' => 'Brand Story',
                ] as $field => $label)

                    <div class="md:col-span-2">

                        <label class="text-sm font-semibold text-slate-700">
                            {{ $label }}
                        </label>

                        <textarea
                            name="{{ $field }}"
                            rows="{{ $field === 'description' || $field === 'brand_story' ? 5 : 3 }}"
                            class="mt-2 w-full rounded-xl border-slate-200"
                        >{{ old($field, $business->{$field}) }}</textarea>

                    </div>

                @endforeach

            </div>

            <div class="mt-8 flex justify-end">

                <button
                    class="rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-600"
                >
                    Save Profile
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
