<nav x-data="{ open: false }" class="border-b border-emerald-100/80 bg-white/90 shadow-sm backdrop-blur">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-emerald-700 transition hover:text-emerald-900">
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100">
                    <i class="fa-solid fa-seedling text-xl"></i>
                </span>
                <span class="text-lg font-semibold tracking-tight">PrepToEat</span>
            </a>

            <div class="hidden items-center gap-1 lg:flex">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">Dashboard</a>
                <a href="{{ route('recipes.catalog') }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition {{ request()->routeIs('recipes.catalog') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">Recipes</a>
                @auth
                    <a href="{{ route('recipes.index') }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition {{ request()->routeIs('recipes.index') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">My Kitchen</a>
                    <a href="{{ route('meal-plan.index') }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition {{ request()->routeIs('meal-plan.*') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">Meal Planner</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-50 hover:text-emerald-900">My Kitchen</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-50 hover:text-emerald-900">Meal Planner</a>
                @endauth
            </div>
        </div>

        <div class="hidden items-center gap-4 lg:flex">
            @auth
                <div class="text-right">
                    <p class="text-sm font-semibold text-emerald-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-emerald-700/80">Fueling smart meals</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Log out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-emerald-900 hover:text-emerald-700">Log in</a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    Join free
                    <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            @endauth
        </div>

        <button @click="open = ! open" class="lg:hidden inline-flex items-center justify-center rounded-md border border-emerald-200 bg-white p-2 text-emerald-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <span class="sr-only">Toggle navigation</span>
            <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
        </button>
    </div>

    <!-- Mobile navigation -->
    <div x-cloak x-show="open" x-transition class="lg:hidden border-t border-emerald-100/80 bg-white/95">
        <div class="space-y-1 px-4 py-4">
            <a href="{{ route('dashboard') }}" class="block rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">Dashboard</a>
            <a href="{{ route('recipes.catalog') }}" class="block rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('recipes.catalog') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">Recipes</a>
            @auth
                <a href="{{ route('recipes.index') }}" class="block rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('recipes.index') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">My Kitchen</a>
                <a href="{{ route('meal-plan.index') }}" class="block rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('meal-plan.*') ? 'bg-emerald-100 text-emerald-900' : 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900' }}">Meal Planner</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-emerald-700 transition hover:bg-emerald-50 hover:text-emerald-900">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Log out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900">My Kitchen</a>
                <a href="{{ route('login') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900">Meal Planner</a>
                <a href="{{ route('login') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900">Log in</a>
                <a href="{{ route('register') }}" class="block rounded-lg px-4 py-3 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">Join free</a>
            @endauth
        </div>
    </div>
</nav>
