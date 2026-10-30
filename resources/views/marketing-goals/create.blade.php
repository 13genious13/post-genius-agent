<x-app-layout>




    <div class="mx-auto max-w-5xl">




        <div class="mb-7">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                Strategy
            </p>




            <h1 class="mt-2 text-3xl font-bold text-slate-950">
                Create Marketing Goal
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Give the future agent team a clear and measurable business outcome.
            </p>
        </div>




        <form
            method="POST"
            action="{{ route('marketing-goals.store') }}"
            class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf




            <x-goal-form
                :businesses="$businesses"
                :selected-business="$selectedBusiness"
            />




            <div class="mt-8 flex justify-end">




                <button class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                    Create Goal
                </button>




            </div>




        </form>




    </div>




</x-app-layout>
