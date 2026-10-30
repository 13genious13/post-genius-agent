<x-app-layout>




    <div class="space-y-7">




        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">




            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                    Execution
                </p>




                <h1 class="mt-2 text-3xl font-bold">
                    Campaigns
                </h1>




                <p class="mt-2 text-sm text-slate-500">
                    Campaign workspaces that will be executed by the PostGenius agent team.
                </p>
            </div>




            <a
                href="{{ route('campaigns.create') }}"
                class="rounded-xl bg-slate-950 px-5 py-3 text-center text-sm font-semibold text-white"
            >
                + New Campaign
            </a>




        </div>




        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">




            @forelse($campaigns as $campaign)




                <a
                    href="{{ route('campaigns.show', $campaign) }}"
                    class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md"
                >




                    <div class="flex items-start justify-between gap-4">




                        <div>




                            <p class="text-xs font-bold uppercase text-indigo-600">
                                {{ str($campaign->campaign_type)->replace('_', ' ')->title() }}
                            </p>




                            <h2 class="mt-2 text-lg font-bold">
                                {{ $campaign->name }}
                            </h2>




                            <p class="mt-1 text-sm text-slate-400">
                                {{ $campaign->business->name }}
                            </p>




                        </div>




                        <x-status-badge :status="$campaign->status" />




                    </div>




                    <p class="mt-5 line-clamp-2 text-sm leading-6 text-slate-500">
                        {{ $campaign->objective }}
                    </p>




                    <div class="mt-5 flex flex-wrap gap-2">




                        @foreach($campaign->platforms ?? [] as $platform)




                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
{{ ucfirst($platform) }}
</span>




                        @endforeach




                    </div>




                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">




<span class="text-xs text-slate-400">
AI Brief
</span>




                        <span class="text-xs font-semibold {{ $campaign->latestBrief ? 'text-emerald-600' : 'text-amber-600' }}">
{{ $campaign->latestBrief ? 'Ready' : 'Not Generated' }}
</span>




                    </div>




                </a>




            @empty




                <div class="col-span-full rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">




                    <h2 class="text-xl font-bold">
                        No campaigns yet
                    </h2>




                    <p class="mt-2 text-sm text-slate-500">
                        Create your first campaign architecture.
                    </p>




                </div>




            @endforelse




        </div>




        {{ $campaigns->links() }}




    </div>




</x-app-layout>
