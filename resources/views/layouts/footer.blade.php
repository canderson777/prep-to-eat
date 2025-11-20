<footer class="bg-emerald-900 dark:bg-gray-900 text-emerald-50 dark:text-gray-300 transition-colors duration-300">
    <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-3">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-emerald-100 dark:text-emerald-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-700 dark:bg-emerald-800">
                        <i class="fa-solid fa-seedling text-lg"></i>
                    </span>
                    <span class="text-xl font-semibold tracking-wide">PrepToEat</span>
                </a>
                <p class="mt-4 text-sm text-emerald-100/80 dark:text-gray-400">
                    Transform the way you plan, cook, and enjoy wholesome meals. PrepToEat blends trusted nutrition with AI-assisted planning to keep you inspired every day.
                </p>
                <div class="mt-4 flex gap-4 text-lg">
                    <a href="https://www.instagram.com" class="transition hover:text-white dark:hover:text-emerald-400" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.pinterest.com" class="transition hover:text-white dark:hover:text-emerald-400" aria-label="Pinterest"><i class="fa-brands fa-pinterest"></i></a>
                    <a href="https://www.youtube.com" class="transition hover:text-white dark:hover:text-emerald-400" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-1">
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200 dark:text-emerald-500">Explore</h3>
                    <ul class="mt-4 space-y-3 text-sm text-emerald-100/80 dark:text-gray-400">
                        <li><a class="transition hover:text-white dark:hover:text-emerald-400" href="{{ url('/') }}">Home</a></li>
                        <li><a class="transition hover:text-white dark:hover:text-emerald-400" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a class="transition hover:text-white dark:hover:text-emerald-400" href="{{ route('recipes.catalog') }}">Recipes</a></li>
                        @auth
                            <li><a class="transition hover:text-white dark:hover:text-emerald-400" href="{{ route('recipes.index') }}">My Kitchen</a></li>
                        @else
                            <li><a class="transition hover:text-white dark:hover:text-emerald-400" href="{{ route('login') }}">My Kitchen</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200 dark:text-emerald-500">Features</h3>
                    <ul class="mt-4 space-y-3 text-sm text-emerald-100/80 dark:text-gray-400">
                        <li>Smart Recipe Extraction</li>
                        <li>Weekly & Monthly Meal Plans</li>
                        <li>Shareable Recipe Collections</li>
                        <li>AI Chat Cooking Coach</li>
                    </ul>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200 dark:text-emerald-500">Stay inspired</h3>
                <p class="mt-4 text-sm text-emerald-100/80 dark:text-gray-400">Get seasonal recipes, prep guides, and kitchen shortcuts delivered weekly.</p>
                <form class="mt-6 flex flex-col gap-3 sm:flex-row" action="#" method="post">
                    <label class="w-full sm:flex-1">
                        <span class="sr-only">Email address</span>
                        <input type="email" name="email" placeholder="you@example.com" class="w-full rounded-md border border-emerald-700 bg-emerald-800/60 dark:bg-gray-800 dark:border-gray-700 px-4 py-3 text-sm placeholder:text-emerald-200/60 dark:placeholder:text-gray-500 focus:border-white dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-white/40 dark:focus:ring-emerald-500/40" required>
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-white dark:bg-emerald-600 px-5 py-3 text-sm font-semibold text-emerald-900 dark:text-white transition hover:bg-emerald-100 dark:hover:bg-emerald-700">
                        Join
                    </button>
                </form>
                <p class="mt-3 text-xs text-emerald-100/70 dark:text-gray-500">We respect your inbox and never share your information.</p>
            </div>
        </div>

        <div class="mt-10 border-t border-emerald-800 dark:border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-emerald-100/70 dark:text-gray-500">
            <div>
                © {{ date('Y') }} PrepToEat. Nourish smarter. Live better.
            </div>
            <div
                x-data="{
                    darkMode: document.documentElement.classList.contains('dark'),
                    mediaQuery: null,
                    mediaCleanup: null,
                    init() {
                        this.syncFromPreference();
                        this.attachSystemListener();
                    },
                    toggle() {
                        this.darkMode = !this.darkMode;
                        this.persistPreference();
                    },
                    syncFromPreference() {
                        try {
                            const stored = localStorage.getItem('darkMode');
                            if (stored === null) {
                                this.darkMode = this.systemPrefersDark();
                            } else {
                                this.darkMode = stored === 'true';
                            }
                        } catch (error) {
                            this.darkMode = this.systemPrefersDark();
                        }
                    },
                    persistPreference() {
                        try {
                            localStorage.setItem('darkMode', this.darkMode);
                        } catch (error) {
                            // Ignore persistence errors (e.g., private mode)
                        }
                    },
                    systemPrefersDark() {
                        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    },
                    attachSystemListener() {
                        if (!window.matchMedia) {
                            return;
                        }

                        this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                        const handler = event => {
                            if (localStorage.getItem('darkMode') === null) {
                                this.darkMode = event.matches;
                            }
                        };

                        this.mediaQuery.addEventListener('change', handler);
                        this.mediaCleanup = () => this.mediaQuery.removeEventListener('change', handler);
                    },
                    destroy() {
                        if (this.mediaCleanup) {
                            this.mediaCleanup();
                        }
                    }
                }"
                x-init="init()"
                x-effect="document.documentElement.classList.toggle('dark', darkMode); document.documentElement.dataset.theme = darkMode ? 'dark' : 'light';"
                x-on:beforeunload.window="destroy()"
            >
                <button 
                    @click="toggle()" 
                    type="button" 
                    class="inline-flex items-center gap-2 rounded-full bg-emerald-800/50 dark:bg-gray-800 px-3 py-1.5 text-emerald-100 dark:text-gray-300 transition hover:bg-emerald-800 dark:hover:bg-gray-700 hover:text-white dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-emerald-900 dark:focus:ring-offset-gray-900"
                    aria-label="Toggle dark mode"
                >
                    <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'">Dark Mode</span>
                </button>
            </div>
        </div>
    </div>
</footer>
