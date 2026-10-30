<x-app-layout>




    <div class="mx-auto max-w-6xl">




        <div class="mb-7">




            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                Campaign Architecture
            </p>




            <h1 class="mt-2 text-3xl font-bold">
                Create Campaign
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Define the campaign context the agent team will execute against.
            </p>




        </div>




        <form
            method="POST"
            action="{{ route('campaigns.store') }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf




            <x-campaign-form
                :businesses="$businesses"
                :goals="$goals"
                :audiences="$audiences"
                :selected-business="$selectedBusiness"
                :selected-goal="$selectedGoal"
            />




            <div class="mt-8 flex justify-end">




                <button class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white">
                    Create Campaign
                </button>




            </div>




        </form>




    </div>




</x-app-layout>
