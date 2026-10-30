<x-app-layout>

    <div class="space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Brand Intelligence
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Define how PostGenius Agent should understand and communicate your brand.
            </p>
        </div>

        <x-business-tabs :business="$business" />

        <form
            method="POST"
            action="{{ route('businesses.brand.update', $business) }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="text-sm font-semibold">Brand Voice</label>

                    <select
                        name="brand_voice"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                        @foreach([
                        '',
                        'Professional',
                        'Authoritative',
                        'Friendly',
                        'Conversational',
                        'Premium',
                        'Bold',
                        'Educational',
                        'Inspirational',
                        'Technical',
                        ] as $voice)

                            <option
                                value="{{ $voice }}"
                                @selected(old('brand_voice', $business->brand_voice) === $voice)
                            >
                            {{ $voice ?: 'Select Brand Voice' }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">
                        Logo URL
                    </label>

                    <input
                        type="url"
                        name="logo_url"
                        value="{{ old('logo_url', $business->logo_url) }}"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

                @foreach([
                'brand_traits_text' => [
                'Brand Traits',
                $business->brand_traits
                ],
                'words_to_use_text' => [
                'Words / Phrases to Use',
                $business->words_to_use
                ],
                'words_to_avoid_text' => [
                'Words / Phrases to Avoid',
                $business->words_to_avoid
                ],
                'target_locations_text' => [
                'Target Locations',
                $business->target_locations
                ],
                'keywords_text' => [
                'Important Keywords / Topics',
                $business->keywords
                ],
                'marketing_objectives_text' => [
                'Current Marketing Objectives',
                $business->marketing_objectives
                ],
                ] as $field=>[$label, $values])

                    <div>

                        <label class="text-sm font-semibold">
                            {{ $label }}
                        </label>

                        <textarea
                            name="{{ $field }}"
                            rows="4"
                            class="mt-2 w-full rounded-xl border-slate-200"
                            placeholder="One per line or comma separated"
                        >{{ old($field, implode("\n", $values ?? [])) }}</textarea>

                    </div>

                @endforeach

                <div class="md:col-span-2">

                    <h3 class="mb-4 font-bold text-slate-900">
                        Brand Colors
                    </h3>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                        @foreach([
                        'primary_color' => 'Primary',
                        'secondary_color' => 'Secondary',
                        'accent_color' => 'Accent',
                        ] as $field => $label)

                            <div>

                                <label class="text-xs font-semibold text-slate-500">
                                    {{ $label }}
                                </label>

                                <div class="mt-2 flex gap-2">

                                    <input
                                        type="color"
                                        value="{{ old($field, $business->{$field}) ?: '#6366f1' }}"
                                        oninput="this.nextElementSibling.value=this.value"
                                        class="h-11 w-14 rounded-lg border-slate-200"
                                    >

                                    <input
                                        name="{{ $field }}"
                                        value="{{ old($field, $business->{$field}) }}"
                                        placeholder="#6366F1"
                                        class="min-w-0 flex-1 rounded-xl border-slate-200"
                                    >

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

            <div class="mt-8 flex justify-end">

                <button class="rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-600">
                    Save Brand Profile
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
