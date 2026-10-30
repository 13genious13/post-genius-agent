@props(['business'])

<div class="overflow-x-auto">

    <div class="flex min-w-max gap-1 rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm">

        @php
            $tabs = [
            [
            'label' => 'Overview',
            'route' => 'businesses.show',
            'active' => 'businesses.show',
            ],
            [
            'label' => 'Profile',
            'route' => 'businesses.edit',
            'active' => 'businesses.edit',
            ],
            [
            'label' => 'Products & Services',
            'route' => 'businesses.products.index',
            'active' => 'businesses.products.*',
            ],
            [
            'label' => 'Audiences',
            'route' => 'businesses.audiences.index',
            'active' => 'businesses.audiences.*',
            ],
            [
            'label' => 'Competitors',
            'route' => 'businesses.competitors.index',
            'active' => 'businesses.competitors.*',
            ],
            [
            'label' => 'Brand',
            'route' => 'businesses.brand.edit',
            'active' => 'businesses.brand.*',
            ],
            [
            'label' => 'AI Intelligence',
            'route' => 'businesses.intelligence.show',
            'active' => 'businesses.intelligence.*',
            ],
            ];
        @endphp

        @foreach($tabs as $tab)

            <a
                href="{{ route($tab['route'], $business) }}"
                class="rounded-xl px-4 py-2.5 text-sm font-semibold transition
 {{ request()->routeIs($tab['active'])
 ? 'bg-slate-950 text-white'
 : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                {{ $tab['label'] }}
            </a>

        @endforeach

    </div>

</div>
