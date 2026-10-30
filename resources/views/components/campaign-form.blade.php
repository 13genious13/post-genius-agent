@props([
'campaign' => null,
'businesses' => collect(),
'goals' => collect(),
'audiences' => collect(),
'selectedBusiness' => null,
'selectedGoal' => null,
])




@php
    $editing=filled($campaign);




    $selectedPlatforms = old(
    'platforms',
    $campaign?->platforms ?? $selectedGoal?->platforms ?? []
    );




    $selectedFormats = old(
    'content_formats',
    $campaign?->content_formats ?? []
    );




    $selectedAudiences = collect(
    old(
    'target_audience_ids',
    $campaign?->target_audience_ids ?? []
    )
    )->map(fn ($id) => (int) $id)->all();
@endphp




<div class="grid grid-cols-1 gap-6 md:grid-cols-2">




    @unless($editing)




        <div>
            <label class="text-sm font-semibold">
                Business *
            </label>




            <select
                name="business_id"
                required
                class="mt-2 w-full rounded-xl border-slate-200"
            >
                <option value="">Select Business</option>




                @foreach($businesses as $business)




                    <option
                        value="{{ $business->id }}"
                        @selected(
                        (int) old(
                    'business_id',
                    $selectedBusiness?->id
                    ) === $business->id
                    )
                    >
                    {{ $business->name }}
                    </option>




                @endforeach
            </select>




            <p class="mt-1 text-xs text-slate-400">
                Select the business first. When creating from a goal, it is preselected.
            </p>
        </div>




    @endunless




    <div>
        <label class="text-sm font-semibold">
            Marketing Goal
        </label>




        <select
            name="marketing_goal_id"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
            <option value="">No linked goal</option>




            @foreach($goals as $goal)




                <option
                    value="{{ $goal->id }}"
                    @selected(
                    (int) old(
                'marketing_goal_id',
                $campaign?->marketing_goal_id
                ?? $selectedGoal?->id
                ) === $goal->id
                )
                >
                {{ $goal->name }}
                </option>




            @endforeach
        </select>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Campaign Name *
        </label>




        <input
            name="name"
            required
            value="{{ old('name', $campaign?->name) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
            placeholder="AI Development Lead Generation – Q4"
        >
    </div>




    <div>
        <label class="text-sm font-semibold">
            Campaign Type
        </label>




        <select
            name="campaign_type"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
            @foreach([
            'integrated' => 'Integrated',
            'social_media' => 'Social Media',
            'content' => 'Content',
            'lead_generation' => 'Lead Generation',
            'paid_media' => 'Paid Media',
            'launch' => 'Launch',
            'awareness' => 'Awareness',
            'engagement' => 'Engagement',
            'retention' => 'Retention',
            ] as $value => $label)




                <option
                    value="{{ $value }}"
                    @selected(
                    old(
                'campaign_type',
                $campaign?->campaign_type ?? 'integrated'
                ) === $value
                )
                >
                {{ $label }}
                </option>




            @endforeach
        </select>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Status
        </label>




        <select
            name="status"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
            @foreach([
            'draft',
            'planning',
            'ready',
            'active',
            'paused',
            'completed',
            'cancelled',
            ] as $status)




                <option
                    value="{{ $status }}"
                    @selected(
                    old(
                'status',
                $campaign?->status ?? 'draft'
                ) === $status
                )
                >
                {{ ucfirst($status) }}
                </option>




            @endforeach
        </select>
    </div>




    <div class="md:col-span-2">
        <label class="text-sm font-semibold">
            Campaign Objective *
        </label>




        <textarea
            name="objective"
            required
            rows="4"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old(
'objective',
$campaign?->objective
?? $selectedGoal?->description
) }}</textarea>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Offer
        </label>




        <textarea
            name="offer"
            rows="3"
            class="mt-2 w-full rounded-xl border-slate-200"
            placeholder="What are we offering?"
        >{{ old('offer', $campaign?->offer) }}</textarea>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Call to Action
        </label>




        <textarea
            name="call_to_action"
            rows="3"
            class="mt-2 w-full rounded-xl border-slate-200"
            placeholder="Book a consultation / Start free trial..."
        >{{ old('call_to_action', $campaign?->call_to_action) }}</textarea>
    </div>




    <div class="md:col-span-2">




        <label class="text-sm font-semibold">
            Target Audiences
        </label>




        @if($audiences->isEmpty())




            <p class="mt-2 rounded-xl bg-amber-50 p-3 text-sm text-amber-700">
                No audience personas loaded for this business. You can create the campaign without one and add audiences later.
            </p>




        @else




            <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">




                @foreach($audiences as $audience)




                    <label class="flex gap-3 rounded-xl border border-slate-200 p-4">




                        <input
                            type="checkbox"
                            name="target_audience_ids[]"
                            value="{{ $audience->id }}"
                            @checked(
                            in_array(
                            $audience->id,
                        $selectedAudiences
                        )
                        )
                        class="mt-1 rounded"
                        >




                        <span>
<span class="block text-sm font-semibold">
{{ $audience->name }}
</span>




<span class="mt-1 block text-xs text-slate-400">
{{ $audience->description }}
</span>
</span>




                    </label>




                @endforeach




            </div>




        @endif




    </div>




    <div class="md:col-span-2">




        <label class="text-sm font-semibold">
            Platforms
        </label>




        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">




            @foreach([
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'x' => 'X',
            'tiktok' => 'TikTok',
            'google' => 'Google',
            'email' => 'Email',
            'blog' => 'Blog / SEO',
            ] as $value => $label)




                <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3 text-sm">




                    <input
                        type="checkbox"
                        name="platforms[]"
                        value="{{ $value }}"
                        @checked(
                        in_array(
                        $value,
                        $selectedPlatforms
                        )
                        )
                        class="rounded"
                    >




                    {{ $label }}




                </label>




            @endforeach




        </div>




    </div>




    <div class="md:col-span-2">




        <label class="text-sm font-semibold">
            Content Formats
        </label>




        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">




            @foreach([
            'image_post' => 'Image Posts',
            'carousel' => 'Carousels',
            'short_video' => 'Short Videos',
            'long_video' => 'Long Videos',
            'story' => 'Stories',
            'blog' => 'Blogs',
            'email' => 'Emails',
            'ad_copy' => 'Ad Copy',
            ] as $value => $label)




                <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3 text-sm">




                    <input
                        type="checkbox"
                        name="content_formats[]"
                        value="{{ $value }}"
                        @checked(
                        in_array(
                        $value,
                        $selectedFormats
                        )
                        )
                        class="rounded"
                    >




                    {{ $label }}




                </label>




            @endforeach




        </div>




    </div>




    <div>
        <label class="text-sm font-semibold">
            Target Locations
        </label>




        <textarea
            name="target_locations_text"
            rows="4"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old(
'target_locations_text',
implode(
"\n",
$campaign?->target_locations
?? $selectedGoal?->target_locations
?? []
)
) }}</textarea>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Primary KPIs
        </label>




        <textarea
            name="primary_kpis_text"
            rows="4"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old(
'primary_kpis_text',
implode(
"\n",
$campaign?->primary_kpis
?? array_filter([
$selectedGoal?->primary_kpi
])
)
) }}</textarea>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Budget
        </label>




        <input
            type="number"
            min="0"
            step="0.01"
            name="budget"
            value="{{ old(
'budget',
$campaign?->budget
?? $selectedGoal?->budget
) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    <div>
        <label class="text-sm font-semibold">
            Currency
        </label>




        <select
            name="currency"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
            @foreach(['INR', 'USD', 'GBP', 'EUR', 'CAD', 'AUD'] as $currency)




                <option
                    value="{{ $currency }}"
                    @selected(
                    old(
                'currency',
                $campaign?->currency
                ?? $selectedGoal?->currency
                ?? 'USD'
                ) === $currency
                )
                >
                {{ $currency }}
                </option>




            @endforeach
        </select>
    </div>




    <div>
        <label class="text-sm font-semibold">
            Start Date
        </label>




        <input
            type="date"
            name="start_date"
            value="{{ old(
'start_date',
$campaign?->start_date?->format('Y-m-d')
?? $selectedGoal?->start_date?->format('Y-m-d')
) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    <div>
        <label class="text-sm font-semibold">
            End Date
        </label>




        <input
            type="date"
            name="end_date"
            value="{{ old(
'end_date',
$campaign?->end_date?->format('Y-m-d')
?? $selectedGoal?->end_date?->format('Y-m-d')
) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    @foreach([
    'brand_constraints' => 'Brand Constraints',
    'campaign_constraints' => 'Campaign Constraints',
    'notes' => 'Internal Notes',
    ] as $field => $label)




        <div class="{{ $field === 'notes' ? 'md:col-span-2' : '' }}">




            <label class="text-sm font-semibold">
                {{ $label }}
            </label>




            <textarea
                name="{{ $field }}"
                rows="3"
                class="mt-2 w-full rounded-xl border-slate-200"
            >{{ old(
$field,
$campaign?->{$field}
) }}</textarea>




        </div>




    @endforeach




</div>
