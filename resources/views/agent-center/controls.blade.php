<x-app-layout>




    <div class="mx-auto max-w-5xl space-y-7">




        <div>




            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">
                Agent Control Center
            </p>




            <h1 class="mt-2 text-3xl font-bold">
                {{ $business->name }}
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Define what autonomous agents may and may not do.
            </p>




        </div>




        <form
            method="POST"
            action="{{ route('agent-controls.update', $business) }}"
            class="space-y-6"
        >
            @csrf
            @method('PUT')




            <section class="rounded-[28px] border border-slate-200 bg-white p-6 sm:p-8">




                <h2 class="text-xl font-bold">
                    Autonomy Mode
                </h2>




                <div class="mt-5 grid gap-4 md:grid-cols-3">




                    @foreach([
                    'manual' => [
                    'Manual',
                    'Agents prepare work but require user action.'
                    ],




                    'assisted' => [
                    'Assisted',
                    'Agents may execute safe internal steps.'
                    ],




                    'autonomous' => [
                    'Autonomous',
                    'Agents may execute permitted actions automatically.'
                    ],
                    ] as $value=>[$title, $description])




                        <label class="cursor-pointer rounded-2xl border border-slate-200 p-5">




                            <div class="flex gap-3">




                                <input
                                    type="radio"
                                    name="autonomy_mode"
                                    value="{{ $value }}"
                                    @checked($control->autonomy_mode === $value)
                                >




                                <div>
                                    <p class="font-bold">
                                        {{ $title }}
                                    </p>




                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        {{ $description }}
                                    </p>
                                </div>




                            </div>




                        </label>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6 sm:p-8">




                <h2 class="text-xl font-bold">
                    Agent Permissions
                </h2>




                <div class="mt-6 grid gap-4 md:grid-cols-2">




                    @foreach([
                    'allow_research' => 'Allow Research',
                    'allow_content_generation' => 'Allow Content Generation',
                    'allow_creative_generation' => 'Allow Creative Generation',
                    'allow_scheduling' => 'Allow Scheduling',
                    'allow_publishing' => 'Allow Publishing',
                    ] as $field => $label)




                        <label class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">




<span class="text-sm font-semibold">
{{ $label }}
</span>




                            <input
                                type="checkbox"
                                name="{{ $field }}"
                                value="1"
                                @checked($control->{$field})
                            class="rounded border-slate-300 text-indigo-600"
                            >




                        </label>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6 sm:p-8">




                <h2 class="text-xl font-bold">
                    Approval Rules
                </h2>




                <div class="mt-6 space-y-4">




                    @foreach([
                    'require_content_approval' => 'Require approval before content is finalized',
                    'require_creative_approval' => 'Require approval before creative is finalized',
                    'require_publish_approval' => 'Require approval before publishing',
                    ] as $field => $label)




                        <label class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">




<span class="text-sm font-semibold">
{{ $label }}
</span>




                            <input
                                type="checkbox"
                                name="{{ $field }}"
                                value="1"
                                @checked($control->{$field})
                            class="rounded border-slate-300 text-indigo-600"
                            >




                        </label>




                    @endforeach




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6 sm:p-8">




                <h2 class="text-xl font-bold">
                    Run Limits
                </h2>




                <div class="mt-6 grid gap-5 md:grid-cols-2">




                    <div>




                        <label class="mb-2 block text-sm font-semibold">
                            Maximum Actions Per Run
                        </label>




                        <input
                            type="number"
                            name="max_actions_per_run"
                            min="1"
                            max="100"
                            value="{{ old(
'max_actions_per_run',
$control->max_actions_per_run
) }}"
                            class="w-full rounded-xl border-slate-200"
                        >




                    </div>




                    <div>




                        <label class="mb-2 block text-sm font-semibold">
                            Maximum AI Requests Per Run
                        </label>




                        <input
                            type="number"
                            name="max_ai_requests_per_run"
                            min="1"
                            max="200"
                            value="{{ old(
'max_ai_requests_per_run',
$control->max_ai_requests_per_run
) }}"
                            class="w-full rounded-xl border-slate-200"
                        >




                    </div>




                </div>




            </section>




            <section class="rounded-[28px] border border-slate-200 bg-white p-6 sm:p-8">




                <h2 class="text-xl font-bold">
                    Allowed Platforms
                </h2>




                <div class="mt-5 flex flex-wrap gap-3">




                    @foreach([
                    'facebook',
                    'instagram',
                    'linkedin',
                    'x',
                    'youtube',
                    'blog',
                    ] as $platform)




                        <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-3">




                            <input
                                type="checkbox"
                                name="allowed_platforms[]"
                                value="{{ $platform }}"
                                @checked(
                                in_array(
                                $platform,
                                $control->allowed_platforms ?? []
                            )
                            )
                            class="rounded border-slate-300 text-indigo-600"
                            >




                            <span class="text-sm font-semibold">
{{ ucfirst($platform) }}
</span>




                        </label>




                    @endforeach




                </div>




            </section>




            <button class="w-full rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white hover:bg-indigo-700">
                Save Agent Controls
            </button>




        </form>




    </div>




</x-app-layout>
