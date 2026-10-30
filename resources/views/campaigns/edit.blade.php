<x-app-layout>




    <div class="mx-auto max-w-6xl">




        <div class="mb-7">




            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                Campaign
            </p>




            <h1 class="mt-2 text-3xl font-bold">
                Edit {{ $campaign->name }}
            </h1>




        </div>




        <form
            method="POST"
            action="{{ route('campaigns.update', $campaign) }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf
            @method('PUT')




            <x-campaign-form
                :campaign="$campaign"
                :goals="$goals"
                :audiences="$audiences"
            />




            <div class="mt-8 flex justify-end">




                <button class="rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white">
                    Save Campaign
                </button>




            </div>




        </form>




    </div>




</x-app-layout>
