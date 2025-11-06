<footer class="bg-emerald-900 text-emerald-50">
    <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-3">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-emerald-100">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-700">
                        <i class="fa-solid fa-seedling text-lg"></i>
                    </span>
                    <span class="text-xl font-semibold tracking-wide">PrepToEat</span>
                </a>
                <p class="mt-4 text-sm text-emerald-100/80">
                    Transform the way you plan, cook, and enjoy wholesome meals. PrepToEat blends trusted nutrition with AI-assisted planning to keep you inspired every day.
                </p>
                <div class="mt-4 flex gap-4 text-lg">
                    <a href="https://www.instagram.com" class="transition hover:text-white" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.pinterest.com" class="transition hover:text-white" aria-label="Pinterest"><i class="fa-brands fa-pinterest"></i></a>
                    <a href="https://www.youtube.com" class="transition hover:text-white" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-1">
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200">Explore</h3>
                    <ul class="mt-4 space-y-3 text-sm text-emerald-100/80">
                        <li><a class="transition hover:text-white" href="{{ url('/') }}">Home</a></li>
                        <li><a class="transition hover:text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a class="transition hover:text-white" href="{{ route('recipes.catalog') }}">Recipes</a></li>
                        @auth
                            <li><a class="transition hover:text-white" href="{{ route('recipes.index') }}">My Kitchen</a></li>
                        @else
                            <li><a class="transition hover:text-white" href="{{ route('login') }}">My Kitchen</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200">Features</h3>
                    <ul class="mt-4 space-y-3 text-sm text-emerald-100/80">
                        <li>Smart Recipe Extraction</li>
                        <li>Weekly & Monthly Meal Plans</li>
                        <li>Shareable Recipe Collections</li>
                        <li>AI Chat Cooking Coach</li>
                    </ul>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200">Stay inspired</h3>
                <p class="mt-4 text-sm text-emerald-100/80">Get seasonal recipes, prep guides, and kitchen shortcuts delivered weekly.</p>
                <form class="mt-6 flex flex-col gap-3 sm:flex-row" action="#" method="post">
                    <label class="w-full sm:flex-1">
                        <span class="sr-only">Email address</span>
                        <input type="email" name="email" placeholder="you@example.com" class="w-full rounded-md border border-emerald-700 bg-emerald-800/60 px-4 py-3 text-sm placeholder:text-emerald-200/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40" required>
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-white px-5 py-3 text-sm font-semibold text-emerald-900 transition hover:bg-emerald-100">
                        Join
                    </button>
                </form>
                <p class="mt-3 text-xs text-emerald-100/70">We respect your inbox and never share your information.</p>
            </div>
        </div>

        <div class="mt-10 border-t border-emerald-800 pt-6 text-center text-xs text-emerald-100/70">
            © {{ date('Y') }} PrepToEat. Nourish smarter. Live better.
        </div>
    </div>
</footer>
