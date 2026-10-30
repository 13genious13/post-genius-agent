<x-app-layout>

    <div class="mx-auto max-w-4xl">

        <div class="mb-7">

            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                New Workspace
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                Add Business
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Start with the core information. Additional brand intelligence can be added after creation.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('businesses.store') }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Business Name *
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >

                    @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Industry *
                    </label>

                    <input
                        type="text"
                        name="industry"
                        value="{{ old('industry') }}"
                        required
                        placeholder="e.g. Digital Marketing"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Business Type
                    </label>

                    <select
                        name="business_type"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                        <option value="">Select</option>
                        <option value="b2b">B2B</option>
                        <option value="b2c">B2C</option>
                        <option value="b2b_b2c">B2B + B2C</option>
                        <option value="saas">SaaS</option>
                        <option value="ecommerce">E-commerce</option>
                        <option value="local_business">Local Business</option>
                        <option value="professional_services">Professional Services</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Website
                    </label>

                    <input
                        type="url"
                        name="website"
                        value="{{ old('website') }}"
                        placeholder="https://example.com"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">
                        Business Description *
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        required
                        class="mt-2 w-full rounded-xl border-slate-200"
                        placeholder="Describe what the business does, its customers and what makes it different."
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Country
                    </label>

                    <input
                        type="text"
                        name="country"
                        value="{{ old('country') }}"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        State
                    </label>

                    <input
                        type="text"
                        name="state"
                        value="{{ old('state') }}"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city') }}"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Timezone
                    </label>

                    <input
                        type="text"
                        name="timezone"
                        value="{{ old('timezone', 'Asia/Kolkata') }}"
                        class="mt-2 w-full rounded-xl border-slate-200"
                    >
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">

                <a
                    href="{{ route('businesses.index') }}"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600"
                >
                    Cancel
                </a>

                <button
                    class="rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-600"
                >
                    Create Business
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
