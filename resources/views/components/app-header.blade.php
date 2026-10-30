<header class="sticky top-0 z-30 h-[73px] border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">

    <div class="flex h-full items-center justify-between px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4">

            <button
                type="button"
                @click="sidebarOpen = true"
                class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            <div class="hidden sm:block">
                <p class="text-xs font-medium uppercase tracking-[0.16em] text-slate-400">
                    PostGenius Agent
                </p>

                <p class="text-sm font-semibold text-slate-700">
                    Autonomous Marketing Workspace
                </p>
            </div>

        </div>

        <div class="flex items-center gap-3">

            <div class="hidden items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 sm:flex">

                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                <span class="text-xs font-semibold text-emerald-700">
                    AI System
                </span>

            </div>

            <div
                class="relative"
                x-data="{ open: false }"
            >

                <button
                    type="button"
                    @click="open = !open"
                    class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-slate-100"
                >

                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="hidden text-left md:block">
                        <div class="max-w-[140px] truncate text-sm font-semibold text-slate-800">
                            {{ auth()->user()?->name }}
                        </div>

                        <div class="text-xs capitalize text-slate-400">
                            {{ auth()->user()?->role ?? 'user' }}
                        </div>
                    </div>

                </button>

                <div
                    x-cloak
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl"
                >

                    @if(Route::has('profile.edit'))
                        <a
                            href="{{ route('profile.edit') }}"
                            class="block rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        >
                            Profile Settings
                        </a>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl px-4 py-2.5 text-left text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Sign Out
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>
