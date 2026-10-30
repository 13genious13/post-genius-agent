<x-app-layout>

    <div class="space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Products & Services
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Tell the AI exactly what {{ $business->name }} sells.
            </p>
        </div>

        <x-business-tabs :business="$business" />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">

            <section class="xl:col-span-2 rounded-[28px] border border-slate-200 bg-white p-6">

                <h2 class="text-lg font-bold">
                    Add Product / Service
                </h2>

                <form
                    method="POST"
                    action="{{ route('businesses.products.store', $business) }}"
                    class="mt-6 space-y-4"
                >
                    @csrf

                    <select name="type" class="w-full rounded-xl border-slate-200">
                        <option value="service">Service</option>
                        <option value="product">Product</option>
                    </select>

                    <input
                        name="name"
                        required
                        placeholder="Name"
                        class="w-full rounded-xl border-slate-200"
                    >

                    <input
                        name="category"
                        placeholder="Category"
                        class="w-full rounded-xl border-slate-200"
                    >

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Description"
                        class="w-full rounded-xl border-slate-200"
                    ></textarea>

                    <textarea
                        name="unique_selling_point"
                        rows="3"
                        placeholder="Unique selling point"
                        class="w-full rounded-xl border-slate-200"
                    ></textarea>

                    <div class="grid grid-cols-2 gap-3">

                        <input
                            type="number"
                            step="0.01"
                            name="price_from"
                            placeholder="Price from"
                            class="rounded-xl border-slate-200"
                        >

                        <input
                            type="number"
                            step="0.01"
                            name="price_to"
                            placeholder="Price to"
                            class="rounded-xl border-slate-200"
                        >

                    </div>

                    <input
                        name="currency"
                        placeholder="Currency e.g. INR"
                        class="w-full rounded-xl border-slate-200"
                    >

                    <input
                        type="url"
                        name="url"
                        placeholder="Product/service URL"
                        class="w-full rounded-xl border-slate-200"
                    >

                    <button class="w-full rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white">
                        Add
                    </button>

                </form>

            </section>

            <section class="xl:col-span-3 space-y-4">

                @forelse($items as $item)

                    <div class="rounded-[24px] border border-slate-200 bg-white p-5">

                        <div class="flex items-start justify-between gap-5">

                            <div>

 <span class="text-xs font-bold uppercase text-indigo-600">
 {{ $item->type }}
 </span>

                                <h3 class="mt-1 text-lg font-bold">
                                    {{ $item->name }}
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    {{ $item->description }}
                                </p>

                            </div>

                            <form
                                method="POST"
                                action="{{ route('businesses.products.destroy', [$business, $item]) }}"
                                onsubmit="return confirm('Remove this item?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="text-sm font-semibold text-red-500">
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                @empty

                    <div class="rounded-[28px] border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-400">
                        No products or services added yet.
                    </div>

                @endforelse

            </section>

        </div>

    </div>

</x-app-layout>
