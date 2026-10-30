<x-app-layout>




    <div class="mx-auto max-w-5xl">




        <div class="mb-7">
            <h1 class="text-3xl font-bold text-slate-950">
                Edit Marketing Goal
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                {{ $marketingGoal->business->name }}
            </p>
        </div>




        <form
            method="POST"
            action="{{ route('marketing-goals.update', $marketingGoal) }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf
            @method('PUT')




            <x-goal-form :goal="$marketingGoal" />




            <div class="mt-8 flex justify-end">




                <button class="rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white">
                    Save Goal
                </button>




            </div>




        </form>




    </div>




</x-app-layout>
