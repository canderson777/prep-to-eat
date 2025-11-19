@extends('layouts.app')

        @php
            $recipe = session('recipe');
            $title = session('title');
            $ingredients = session('ingredients');
            $instructions = session('instructions');
            $summary = session('summary');
    $qaQuestion = session('qa_question');
    $qaAnswer = session('qa_answer');
        @endphp

@push('head')
    <style>
        .recipe-rich-text h1,
        .recipe-rich-text h2,
        .recipe-rich-text h3 {
            color: #047857;
            font-weight: 600;
            margin-top: 1.5rem;
        }
        .recipe-rich-text strong {
            color: #047857;
        }
        .recipe-rich-text ul,
        .recipe-rich-text ol {
            margin-left: 1.25rem;
            padding-left: 0.75rem;
        }
        .recipe-rich-text li {
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-white">
        <div class="absolute inset-x-0 top-0 -z-10 h-64 bg-gradient-to-b from-emerald-200/40 via-transparent to-transparent"></div>
        <div class="mx-auto flex max-w-7xl flex-col gap-12 px-4 py-12 sm:px-6 lg:flex-row lg:items-center lg:py-20 lg:px-8">
            <div class="lg:w-1/2">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-emerald-700 shadow-sm ring-1 ring-emerald-200/70">
                    <i class="fa-solid fa-heart-pulse text-emerald-500"></i>
                    Cook smarter, live vibrant
                </span>
                <h1 class="mt-6 text-3xl font-bold text-slate-900 sm:text-4xl lg:text-5xl">
                    AI-powered meal planning for healthy home cooking
                </h1>
                <p class="mt-6 text-base text-slate-700 sm:text-lg">
                    Turn any recipe link into a smart, personalized plan in seconds. PrepToEat keeps your pantry organized, your meals intentional, and your nutrition on track—without the overwhelm.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="#recipe-form" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-700 hover:shadow-xl">
                        Generate a recipe now
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </a>
                    <a href="{{ route('recipes.catalog') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white/80 px-6 py-3 text-sm font-semibold text-emerald-700 shadow ring-1 ring-emerald-200 transition hover:bg-emerald-50">
                        Browse curated recipes
                        <i class="fa-solid fa-utensils"></i>
                    </a>
                </div>
                <dl class="mt-10 grid grid-cols-2 gap-6 text-sm text-emerald-900 sm:grid-cols-4">
                    <div class="rounded-2xl bg-white/80 p-4 shadow ring-1 ring-emerald-100">
                        <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Time saved</dt>
                        <dd class="mt-2 text-2xl font-bold">7 hrs</dd>
                        <p class="text-xs text-slate-500">per week vs. takeout planning</p>
                    </div>
                    <div class="rounded-2xl bg-white/80 p-4 shadow ring-1 ring-emerald-100">
                        <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Budget</dt>
                        <dd class="mt-2 text-2xl font-bold">32%</dd>
                        <p class="text-xs text-slate-500">average savings on groceries</p>
                    </div>
                    <div class="rounded-2xl bg-white/80 p-4 shadow ring-1 ring-emerald-100">
                        <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Nutrition</dt>
                        <dd class="mt-2 text-2xl font-bold">3x</dd>
                        <p class="text-xs text-slate-500">more home-cooked meals weekly</p>
                    </div>
                    <div class="rounded-2xl bg-white/80 p-4 shadow ring-1 ring-emerald-100">
                        <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Community</dt>
                        <dd class="mt-2 text-2xl font-bold">4k+</dd>
                        <p class="text-xs text-slate-500">recipes shared by cooks</p>
                    </div>
                </dl>
            </div>
            <div class="relative lg:w-1/2">
                <div class="absolute -top-8 -right-10 hidden h-32 w-32 rounded-full bg-emerald-100/80 blur-2xl lg:block"></div>
                <div class="relative rounded-3xl bg-white/80 p-6 shadow-2xl ring-1 ring-emerald-100/80">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                <i class="fa-solid fa-calendar-week text-xl"></i>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-emerald-600">Weekly Wellness Plan</p>
                                <p class="text-xs text-slate-500">Sync meals, macros, and grocery lists.</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase text-emerald-700">Live demo</span>
                    </div>
                    <div class="mt-6 space-y-4 text-sm">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            Smart recipe extraction from any URL
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            Auto-build meal plans with drag & drop calendar
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            AI Cooking Coach with substitutions & tips
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            Share recipes securely with friends & family
                        </div>
                    </div>
                    <div class="mt-6 rounded-2xl bg-emerald-50/70 px-5 py-4 text-sm text-emerald-800">
                        "PrepToEat cut our takeout bill dramatically and made healthy eating feel exciting again."
                        <p class="mt-3 text-xs font-semibold uppercase tracking-widest text-emerald-500">— Maya, nutrition coach</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white/70">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:gap-12 lg:py-16 lg:px-8">
            <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-emerald-100">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-bowl-food text-xl"></i>
                </span>
                <h3 class="mt-6 text-xl font-semibold text-slate-900">Personalized Recipe Engine</h3>
                <p class="mt-4 text-sm text-slate-600">Drop in a URL or paste ingredients—our AI extracts titles, steps, nutrition, and highlights so you can cook with confidence.</p>
            </div>
            <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-emerald-100">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-calendar-days text-xl"></i>
                </span>
                <h3 class="mt-6 text-xl font-semibold text-slate-900">Weekly & Monthly Planner</h3>
                <p class="mt-4 text-sm text-slate-600">Schedule meals across the week, sync with your pantry, and generate grocery lists. Keep your wellness goals on track effortlessly.</p>
            </div>
            <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-emerald-100">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-message text-xl"></i>
                </span>
                <h3 class="mt-6 text-xl font-semibold text-slate-900">AI Cooking Coach & Sharing</h3>
                <p class="mt-4 text-sm text-slate-600">Ask questions in real time, find healthy swaps, and share curated recipe cards with friends, family, or clients.</p>
            </div>
        </div>
    </section>

    <section id="recipe-form" class="relative">
        <div class="absolute inset-x-0 -top-10 -z-10 h-64 bg-gradient-to-b from-white via-emerald-50/60 to-transparent"></div>
        <div class="mx-auto max-w-4xl px-4 pb-16 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-white/90 p-8 shadow-2xl ring-1 ring-emerald-100">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Step 1</p>
                        <h2 class="text-2xl font-bold text-slate-900">Paste a recipe URL or your notes</h2>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <i class="fa-solid fa-lock text-emerald-500"></i>
                        Secure & private — we never store shared links.
                    </div>
                </div>

                <div id="spinner" class="mt-6 hidden rounded-2xl bg-emerald-50/80 px-4 py-4 text-sm text-emerald-700">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 animate-spin items-center justify-center rounded-full border-[3px] border-emerald-100 border-b-emerald-500"></span>
                        Parsing your recipe. This usually takes less than 10 seconds.
                    </div>
                </div>

                @if(session('error') && !session('recipe'))
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('recipe.generate') }}" id="recipeForm" enctype="multipart/form-data" novalidate class="mt-6 space-y-5">
                    @csrf
                    <label for="recipe_link" class="block text-sm font-semibold text-slate-700">Recipe link or text</label>
                    <textarea name="recipe_link" id="recipe_link" rows="6" placeholder="Paste the recipe URL, ingredients list, or cooking instructions here..." required class="w-full rounded-2xl border border-emerald-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"></textarea>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <button type="submit" onclick="document.getElementById('spinner').classList.remove('hidden');" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            Get recipe insights
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </button>
                        <button type="button" onclick="window.location.href='/?clear=1'" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                            Clear & start over
                        </button>
                        <a href="{{ route('recipes.catalog') }}" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
                            Need inspiration? Explore healthy recipes
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                </form>

                @guest
                    <div class="mt-6 rounded-2xl bg-emerald-50/80 px-4 py-4 text-sm text-emerald-700">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <p><strong>Tip:</strong> Save up to 4 recipes locally as a guest. Sign up free to keep unlimited favorites in your kitchen.</p>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-700">Stored locally <span id="local-count" class="font-bold">0</span>/4</span>
                                <a href="{{ route('recipes.local') }}" class="font-semibold text-emerald-700 hover:text-emerald-900">View local recipes</a>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </section>

        @if($recipe)
        <section class="bg-white/70">
            <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:py-16 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-[2fr,1fr]">
                    <div class="rounded-3xl bg-white/90 p-8 shadow-xl ring-1 ring-emerald-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Step 2</p>
                                <h2 class="text-2xl font-bold text-slate-900">Your smart recipe card</h2>
                            </div>
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase text-emerald-700">AI formatted</span>
                        </div>
                        <div class="recipe-rich-text mt-6 space-y-5 text-sm leading-relaxed text-slate-700">
                            {!! $recipe !!}
                        </div>

                @auth
                            <form id="saveRecipeForm" action="{{ route('recipes.save') }}" method="POST" class="mt-8 space-y-5">
                        @csrf
                        <input type="hidden" name="title" value="{{ $title ?? '' }}">
                        <input type="hidden" name="ingredients" value="{{ $ingredients ?? '' }}">
                        <input type="hidden" name="instructions" value="{{ $instructions ?? '' }}">
                        <input type="hidden" name="summary" value="{{ $summary ?? '' }}">

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="text-sm font-semibold text-slate-700" for="category">Category</label>
                                    <div class="md:col-span-1">
                                        <select name="category" id="category" class="w-full rounded-xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                <option value="">Select a category</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Dessert">Dessert</option>
                                <option value="Snack">Snack</option>
                                <option value="Appetizer">Appetizer</option>
                                <option value="Beverage">Beverage</option>
                                <option value="other">Other</option>
                            </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <input type="text" name="custom_category" id="custom_category" placeholder="Or enter your own category" class="hidden w-full rounded-xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100" />
                            @error('category')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            @error('custom_category')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                                </div>

                                @php
                                    $allTags = \App\Models\RecipeTag::orderBy('name')->get();
                                @endphp
                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="text-sm font-semibold text-slate-700">Dietary Tags (optional)</label>
                                    <div class="md:col-span-2">
                                        <div class="mt-2 flex flex-wrap gap-3">
                                            @foreach($allTags as $tag)
                                                <label class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-xs font-semibold shadow-sm ring-1 ring-emerald-100 cursor-pointer transition hover:bg-emerald-50">
                                                    <input 
                                                        type="checkbox" 
                                                        name="tags[]" 
                                                        value="{{ $tag->id }}"
                                                        class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500"
                                                    >
                                                    <span>{{ $tag->icon ?? '' }} {{ $tag->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-2 text-xs text-slate-500">Select tags that match this recipe's dietary characteristics.</p>
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    <i class="fa-solid fa-bookmark"></i>
                                    Save to My Kitchen
                                </button>
                    </form>

                    @php
                        $userRecipes = Auth::user()->savedRecipes()->orderBy('title')->get();
                        $selectedRecipeId = optional($userRecipes->firstWhere('title', $title ?? ''))->id;
                    @endphp

                            <div class="mt-10 rounded-3xl bg-emerald-50/80 p-6 ring-1 ring-emerald-100">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Step 3</p>
                                        <h3 class="text-xl font-semibold text-emerald-900">Schedule this meal</h3>
                                    </div>
                                    <button type="button" id="togglePlanForm" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                        Add to calendar
                                    </button>
                                </div>

                                <form id="planForm" action="{{ route('meal-plan.store') }}" method="POST" class="mt-6 hidden space-y-4 rounded-2xl bg-white/80 p-5 shadow-sm ring-1 ring-emerald-100">
                            @csrf
                                    <div>
                                        <label for="plan_recipe_id" class="text-sm font-semibold text-slate-700">Choose from your cookbook</label>
                                        <select id="plan_recipe_id" name="saved_recipe_id" required class="mt-2 w-full rounded-xl border border-emerald-100 px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                            <option value="">Select a saved recipe</option>
                                @foreach($userRecipes as $saved)
                                    <option value="{{ $saved->id }}" {{ $selectedRecipeId === $saved->id ? 'selected' : '' }}>{{ $saved->title }}</option>
                                @endforeach
                            </select>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="text-sm font-semibold text-slate-700" for="plan_date">Date</label>
                                        <input type="date" id="plan_date" name="planned_for" required class="rounded-xl border border-emerald-100 px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100" />
                                        <label class="text-sm font-semibold text-slate-700" for="plan_slot">Meal slot</label>
                                        <select id="plan_slot" name="meal_type" class="rounded-xl border border-emerald-100 px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                <option value="">Anytime</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Snack">Snack</option>
                            </select>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-slate-700" for="plan_notes">Prep notes</label>
                                        <textarea id="plan_notes" name="notes" rows="3" class="mt-2 w-full rounded-xl border border-emerald-100 px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100" placeholder="Remind me to marinate overnight, double batch for lunch, etc."></textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        Schedule meal
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-8 rounded-2xl bg-emerald-50/70 p-5 text-sm text-emerald-700">
                                <p class="font-semibold">Want to save this recipe or add it to your calendar?</p>
                                <p class="mt-2">Create a free account to unlock unlimited saved recipes and weekly planning tools.</p>
                                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                    <button type="button" id="guest-save-recipe" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        Save locally (up to 4)
                                        <i class="fa-solid fa-download"></i>
                                    </button>
                                    <a href="{{ route('recipes.local') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                        View local recipes
                                    </a>
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                            Join PrepToEat
                                        </a>
                                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                            Log in
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endauth

                        <div id="qa" class="mt-12 rounded-3xl border border-emerald-100 bg-white/80 p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Cooking question?</p>
                                    <h3 class="text-xl font-semibold text-emerald-900">Ask the PrepToEat assistant</h3>
                                </div>
                                <i class="fa-solid fa-message text-2xl text-emerald-500"></i>
                            </div>
                            <form method="POST" action="{{ route('recipe.ask') }}" class="mt-6 space-y-4">
                                @csrf
                                <label for="question" class="text-sm font-semibold text-slate-700">Your question</label>
                                <textarea id="question" name="question" rows="3" placeholder="Need a dairy-free swap? Want to reduce sodium? Ask away." required class="w-full rounded-xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"></textarea>
                                @error('question')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    Ask now
                                </button>
                        </form>

                            @if($qaQuestion && $qaAnswer)
                                <div class="mt-8 rounded-2xl bg-emerald-50/70 p-5 text-sm text-emerald-800">
                                    <p class="font-semibold text-emerald-900">You asked:</p>
                                    <p class="mt-2 text-slate-700">{{ $qaQuestion }}</p>
                                    <div class="mt-4 rounded-xl bg-white/80 p-4 text-slate-700 shadow ring-1 ring-emerald-100">
                                        {{ $qaAnswer }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div class="rounded-3xl bg-white/90 p-6 shadow-xl ring-1 ring-emerald-100">
                            <h3 class="text-lg font-semibold text-slate-900">Healthy kitchen insights</h3>
                            <ul class="mt-4 space-y-4 text-sm text-slate-600">
                                <li class="flex items-start gap-3"><i class="fa-solid fa-seedling text-emerald-500"></i><span>Home cooks who plan their meals reduce impulse takeout orders by <strong>48%</strong> and boost nutrient diversity.</span></li>
                                <li class="flex items-start gap-3"><i class="fa-solid fa-wallet text-emerald-500"></i><span>PrepToEat users report saving an average of <strong>$120/month</strong> by cooking intentional, seasonal meals.</span></li>
                                <li class="flex items-start gap-3"><i class="fa-solid fa-apple-whole text-emerald-500"></i><span>Nutrition studies show that consistent, home-cooked meals correlate with better gut health and energy levels.</span></li>
                            </ul>
                        </div>

                        <div class="rounded-3xl bg-emerald-600 p-6 text-white shadow-xl">
                            <h3 class="text-lg font-semibold">Grow your kitchen confidence</h3>
                            <p class="mt-3 text-sm text-emerald-50">Explore curated healthy recipes, save your best discoveries, and sync them with your meal planner—all inside PrepToEat.</p>
                            <div class="mt-4 flex flex-col gap-3">
                                <a href="{{ route('recipes.catalog') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                    Discover wholesome recipes
                                    <i class="fa-solid fa-spoon"></i>
                                </a>
                                @auth
                                    <a href="{{ route('recipes.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-500/80 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                                        Visit My Kitchen
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-500/80 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                                        Create a free account
                                    </a>
                                @endauth
                            </div>
                        </div>

                        @auth
                            <div id="importBanner" class="hidden rounded-3xl border border-emerald-100 bg-white/90 p-5 text-sm text-slate-700 shadow">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-emerald-700">We spotted <span id="importCount">0</span> recipes saved on this device.</p>
                                        <p class="text-xs text-slate-500">Bring them into your PrepToEat kitchen so everything stays synced.</p>
                                    </div>
                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <button id="importBtn" type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                            Import to my account
                                        </button>
                                        <button id="dismissImport" type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100">
                                            Not now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                <div class="relative mt-16 overflow-hidden rounded-3xl bg-emerald-900 px-6 py-8 text-emerald-50 shadow-xl">
                    <div class="absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-emerald-700/40 blur-2xl"></div>
                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-widest text-emerald-200">Built for busy kitchens</h3>
                            <p class="mt-3 text-sm">Whether you are meal-prepping for one or running a family table, PrepToEat keeps your routines fresh, healthy, and joyful.</p>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-people-group text-emerald-200"></i>
                                <span>Shared cookbooks let you collaborate with partners, dietitians, or clients.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-scale-balanced text-emerald-200"></i>
                                <span>Balanced macros and ingredient insights keep wellness goals in focus.</span>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-mobile-screen-button text-emerald-200"></i>
                                <span>Optimized for mobile—manage your kitchen from the grocery aisle.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-list-check text-emerald-200"></i>
                                <span>Generate smart shopping lists that sync with your weekly schedule.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
@endsection

@auth
    @push('scripts')
    <script>
            (function () {
                const KEY = 'guestRecipes';
                const SEEN = 'import_banner_dismissed';

                const list = (() => {
                    try { return JSON.parse(localStorage.getItem(KEY)) || []; }
                    catch { return []; }
                })();
                if (!list.length || localStorage.getItem(SEEN) === '1') return;

                const banner = document.getElementById('importBanner');
                const count = document.getElementById('importCount');
                const importBtn = document.getElementById('importBtn');
                const dismissBtn = document.getElementById('dismissImport');
                if (!banner || !count || !importBtn || !dismissBtn) return;

                count.textContent = list.length;
                banner.classList.remove('hidden');

                importBtn.addEventListener('click', async () => {
                    try {
                        const res = await fetch("{{ route('recipes.import') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ recipes: list }),
                        });
                        if (!res.ok) throw new Error('Import failed');
                        localStorage.removeItem(KEY);
                        localStorage.removeItem('free_uses');
                        alert('Imported! Your recipes are now in your account.');
                        window.location.href = "{{ route('recipes.index') }}";
                    } catch (error) {
                        alert('Could not import. Please try again.');
                    }
                });

                dismissBtn.addEventListener('click', () => {
                    localStorage.setItem(SEEN, '1');
                    banner.classList.add('hidden');
                });
            })();
        </script>
    @endpush
@endauth

@push('scripts')
    <script>
        (function () {
            const categorySelect = document.getElementById('category');
            const customCategory = document.getElementById('custom_category');
            if (categorySelect && customCategory) {
                categorySelect.addEventListener('change', function () {
                    const isOther = this.value === 'other';
                    customCategory.classList.toggle('hidden', !isOther);
                    customCategory.required = isOther;
                });
            }
        })();

        (function () {
            const toggle = document.getElementById('togglePlanForm');
            const form = document.getElementById('planForm');
            if (!toggle || !form) return;

            toggle.addEventListener('click', () => {
                form.classList.toggle('hidden');
            });
        })();

        (function () {
            const KEY = 'guestRecipes';
            const helper = window.PrepToEatGuest = window.PrepToEatGuest || {};

            helper.getStoredRecipes = function () {
                try { return JSON.parse(localStorage.getItem(KEY)) || []; }
                catch { return []; }
            };

            helper.setStoredRecipes = function (items) {
                localStorage.setItem(KEY, JSON.stringify(items));
            };

            helper.updateCounter = function () {
                const label = document.getElementById('local-count');
                if (!label) return;
                label.textContent = helper.getStoredRecipes().length;
            };

            helper.updateCounter();
        })();

        (function () {
            const form = document.getElementById('homeChatForm');
            const log = document.getElementById('homeChatLog');
            if (!form || !log) return;

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                const messageInput = document.getElementById('homeChatMessage');
                const ingredientInput = document.getElementById('homeChatIngredients');
                if (!messageInput) return;

                const message = (messageInput.value || '').trim();
                const ingredients = ingredientInput ? (ingredientInput.value || '').trim() : '';
                if (!message) return;

                appendEntry('You', message);
                messageInput.value = '';
                if (ingredientInput) ingredientInput.value = '';

                const tokenInput = form.querySelector('input[name="_token"]');

                try {
                    const response = await fetch("{{ route('chat.respond') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': tokenInput ? tokenInput.value : '',
                            'Accept': 'application/json',
                        },
                        body: new URLSearchParams({ message, ingredients }),
                    });

                    if (!response.ok) throw new Error('Bad response');
                    const data = await response.json();
                    appendEntry('PrepToEat', data.reply || 'I\'m not sure how to help with that.');
                } catch (error) {
                    appendEntry('PrepToEat', 'The assistant is unavailable right now. Please try again soon.');
                }
            });

            function appendEntry(author, text) {
                const wrapper = document.createElement('div');
                wrapper.className = 'rounded-2xl bg-emerald-50/70 px-4 py-3 text-sm text-slate-700';
                const title = document.createElement('div');
                title.className = 'text-sm font-semibold text-emerald-700';
                title.textContent = author;
                const body = document.createElement('div');
                body.className = 'mt-1 text-slate-600';
                body.textContent = text;
                wrapper.appendChild(title);
                wrapper.appendChild(body);
                log.appendChild(wrapper);
                log.scrollTop = log.scrollHeight;
            }
        })();

        (function () {
            const saveBtn = document.getElementById('guest-save-recipe');
            if (!saveBtn) return;

            const helper = window.PrepToEatGuest || {};
            const MAX = 4;
            const payload = {
                title: @json($title ?: 'Untitled Recipe'),
                ingredients: @json($ingredients ?: ''),
                instructions: @json($instructions ?: ''),
                summary: @json($summary ?: ''),
                recipe_html: @json($recipe ?: ''),
                saved_at: new Date().toISOString(),
            };

            saveBtn.addEventListener('click', () => {
                const current = helper.getStoredRecipes ? helper.getStoredRecipes() : [];
                if (current.length >= MAX) {
                    alert('You have reached the guest limit of 4 recipes. Log in or create an account for unlimited saves.');
                    return;
                }

                const alreadySaved = current.some(item => (item.title || '').toLowerCase() === payload.title.toLowerCase());
                if (alreadySaved) {
                    alert('This recipe is already saved locally. You can view it in My Local Recipes.');
                    return;
                }

                helper.setStoredRecipes ? helper.setStoredRecipes([...current, payload]) : localStorage.setItem('guestRecipes', JSON.stringify([...current, payload]));
                if (helper.updateCounter) helper.updateCounter();
                alert('Recipe saved locally! You can find it in My Local Recipes.');
            });
        })();
    </script>
@endpush
