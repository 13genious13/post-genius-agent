<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    Business Workspace
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-950">
                    {{ $business->name }}
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $business->industry }}

                    @if($business->city)
                        · {{ $business->city }}
                    @endif
                </p>

            </div>

            <div class="flex items-center gap-3">

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
                    <p class="text-xs text-slate-400">Profile</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">
                        {{ $business->profile_completion }}%
                    </p>
                </div>

                <a
                    href="{{ route('businesses.edit', $business) }}"
                    class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white"
                >
                    Edit Profile
                </a>

                <a
                    href="{{ route('marketing-goals.create', ['business' => $business->id]) }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700"
                >
                    Create Goal
                </a>




                <a
                    href="{{ route('campaigns.create', ['business' => $business->id]) }}"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white"
                >
                    Create Campaign
                </a>


            </div>

        </div>

        <x-business-tabs :business="$business" />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            <section class="xl:col-span-2 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-bold text-slate-900">
                    Business Profile
                </h2>

                <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">
                    {{ $business->description }}
                </p>

                <div class="mt-7 grid grid-cols-2 gap-4 md:grid-cols-4">

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">Products / Services</p>
                        <p class="mt-2 text-2xl font-bold">{{ $business->productsServices->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">Audiences</p>
                        <p class="mt-2 text-2xl font-bold">{{ $business->audiences->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">Competitors</p>
                        <p class="mt-2 text-2xl font-bold">{{ $business->competitors->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">Channels</p>
                        <p class="mt-2 text-2xl font-bold">{{ $business->channels->count() }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">
                            Marketing Goals
                        </p>
                        <p class="mt-2 text-2xl font-bold">
                            {{ $business->marketingGoals->count() }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">
                            Campaigns
                        </p>
                        <p class="mt-2 text-2xl font-bold">
                            {{ $business->campaigns->count() }}
                        </p>
                    </div>
                </div>

            </section>

            <section class="rounded-[28px] border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-6">

                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    AI Intelligence
                </p>

                @if($business->latestIntelligence)

                    <h2 class="mt-3 text-lg font-bold text-slate-900">
                        Intelligence Ready
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Version {{ $business->latestIntelligence->version }}
                        generated
                        {{ $business->latestIntelligence->generated_at?->diffForHumans() }}.
                    </p>

                    <a
                        href="{{ route('businesses.intelligence.show', $business) }}"
                        class="mt-5 inline-flex rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white"
                    >
                        View Intelligence
                    </a>

                @else

                    <h2 class="mt-3 text-lg font-bold text-slate-900">
                        Build Business Intelligence
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Complete the profile and let PostGenius Agent build structured marketing intelligence.
                    </p>

                    <a
                        href="{{ route('businesses.intelligence.show', $business) }}"
                        class="mt-5 inline-flex rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white"
                    >
                        Continue Setup
                    </a>

                @endif

            </section>

        </div>

    </div>

</x-app-layout>
