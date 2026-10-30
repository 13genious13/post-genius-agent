@props([
'goal' => null,
'businesses' => collect(),
'selectedBusiness' => null,
])




@php
    $editing=filled($goal);




    $platformOptions = [
    'facebook' => 'Facebook',
    'instagram' => 'Instagram',
    'linkedin' => 'LinkedIn',
    'youtube' => 'YouTube',
    'x' => 'X / Twitter',
    'tiktok' => 'TikTok',
    'google' => 'Google',
    'email' => 'Email',
    'blog' => 'Blog / SEO',
    ];




    $selectedPlatforms = old(
    'platforms',
    $goal?->platforms ?? []
    );
@endphp




<div class="grid grid-cols-1 gap-6 md:grid-cols-2">




    @unless($editing)




        <div class="md:col-span-2">




            <label class="text-sm font-semibold text-slate-700">
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




        </div>




    @endunless




    <div>
        <label class="text-sm font-semibold text-slate-700">
            Goal Name *
        </label>




        <input
            type="text"
            name="name"
            required
            value="{{ old('name', $goal?->name) }}"
            placeholder="e.g. Generate 100 qualified AI development leads"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    <div>




        <label class="text-sm font-semibold text-slate-700">
            Goal Type *
        </label>




        <select
            name="goal_type"
            required
            class="mt-2 w-full rounded-xl border-slate-200"
        >




            @foreach([
            'brand_awareness' => 'Brand Awareness',
            'lead_generation' => 'Lead Generation',
            'sales' => 'Sales',
            'website_traffic' => 'Website Traffic',
            'engagement' => 'Engagement',
            'followers' => 'Follower Growth',
            'app_installs' => 'App Installs',
            'event_promotion' => 'Event Promotion',
            'product_launch' => 'Product Launch',
            'customer_retention' => 'Customer Retention',
            'other' => 'Other',
            ] as $value => $label)




                <option
                    value="{{ $value }}"
                    @selected(
                    old(
                'goal_type',
                $goal?->goal_type
                ) === $value
                )
                >
                {{ $label }}
                </option>




            @endforeach




        </select>




    </div>




    <div class="md:col-span-2">




        <label class="text-sm font-semibold text-slate-700">
            Description
        </label>




        <textarea
            name="description"
            rows="4"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old('description', $goal?->description) }}</textarea>




    </div>




    <div>




        <label class="text-sm font-semibold text-slate-700">
            Primary KPI
        </label>




        <input
            name="primary_kpi"
            value="{{ old('primary_kpi', $goal?->primary_kpi) }}"
            placeholder="Qualified Leads"
            class="mt-2 w-full rounded-xl border-slate-200"
        >




    </div>




    <div class="grid grid-cols-2 gap-3">




        <div>
            <label class="text-sm font-semibold text-slate-700">
                Target
            </label>




            <input
                type="number"
                step="0.01"
                min="0"
                name="target_value"
                value="{{ old('target_value', $goal?->target_value) }}"
                class="mt-2 w-full rounded-xl border-slate-200"
            >
        </div>




        <div>
            <label class="text-sm font-semibold text-slate-700">
                Unit
            </label>




            <input
                name="unit"
                value="{{ old('unit', $goal?->unit) }}"
                placeholder="leads"
                class="mt-2 w-full rounded-xl border-slate-200"
            >
        </div>




    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
            Budget
        </label>




        <input
            type="number"
            min="0"
            step="0.01"
            name="budget"
            value="{{ old('budget', $goal?->budget) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
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
                $goal?->currency ?? 'USD'
                ) === $currency
                )
                >
                {{ $currency }}
                </option>




            @endforeach
        </select>
    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
            Start Date
        </label>




        <input
            type="date"
            name="start_date"
            value="{{ old('start_date', $goal?->start_date?->format('Y-m-d')) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
            End Date
        </label>




        <input
            type="date"
            name="end_date"
            value="{{ old('end_date', $goal?->end_date?->format('Y-m-d')) }}"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
            Status
        </label>




        <select
            name="status"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
            @foreach([
            'draft',
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
                $goal?->status ?? 'draft'
                ) === $status
                )
                >
                {{ ucfirst($status) }}
                </option>




            @endforeach
        </select>
    </div>




    <div>




        <label class="text-sm font-semibold text-slate-700">
            Priority
        </label>




        <select
            name="priority"
            class="mt-2 w-full rounded-xl border-slate-200"
        >
            @foreach([
            1 => '1 - Low',
            2 => '2',
            3 => '3 - Normal',
            4 => '4',
            5 => '5 - Critical',
            ] as $value => $label)




                <option
                    value="{{ $value }}"
                    @selected(
                    (int) old(
                'priority',
                $goal?->priority ?? 3
                ) === $value
                )
                >
                {{ $label }}
                </option>




            @endforeach
        </select>




    </div>




    <div class="md:col-span-2">




        <label class="text-sm font-semibold text-slate-700">
            Target Platforms
        </label>




        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">




            @foreach($platformOptions as $value => $label)




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
                        class="rounded border-slate-300"
                    >




                    {{ $label }}




                </label>




            @endforeach




        </div>




    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
            Target Locations
        </label>




        <textarea
            name="target_locations_text"
            rows="4"
            placeholder="USA&#10;India&#10;Chandigarh"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old(
'target_locations_text',
implode("\n", $goal?->target_locations ?? [])
) }}</textarea>
    </div>




    <div>
        <label class="text-sm font-semibold text-slate-700">
            Secondary KPIs
        </label>




        <textarea
            name="secondary_kpis_text"
            rows="4"
            placeholder="CTR&#10;Conversion Rate&#10;Cost per Lead"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old(
'secondary_kpis_text',
implode("\n", $goal?->secondary_kpis ?? [])
) }}</textarea>
    </div>




    <div class="md:col-span-2">
        <label class="text-sm font-semibold text-slate-700">
            Definition of Success
        </label>




        <textarea
            name="success_definition"
            rows="3"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old(
'success_definition',
$goal?->success_definition
) }}</textarea>
    </div>




    <div class="md:col-span-2">
        <label class="text-sm font-semibold text-slate-700">
            Constraints
        </label>




        <textarea
            name="constraints"
            rows="3"
            class="mt-2 w-full rounded-xl border-slate-200"
        >{{ old('constraints', $goal?->constraints) }}</textarea>
    </div>




</div>
