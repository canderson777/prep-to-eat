@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-white">
        <div class="absolute inset-x-0 top-0 -z-10 h-64 bg-gradient-to-b from-emerald-200/40 via-transparent to-transparent"></div>
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-20 lg:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="lg:w-2/3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-emerald-700 shadow ring-1 ring-emerald-200/80">
                        <i class="fa-solid fa-spoon"></i>
                        Curated for whole-food living
                    </span>
                    <h1 class="mt-6 text-3xl font-bold text-slate-900 sm:text-4xl lg:text-5xl">
                        Your health-forward recipe library
                    </h1>
                    <p class="mt-4 text-base text-slate-700 sm:text-lg">
                        Browse nutrient-dense meals tested by our community and inspired by functional medicine leaders like Dr. Mark Hyman. Save the dishes that fit your goals, sync them to your calendar, and cook with confidence.
                    </p>
                </div>
                <div class="grid gap-3 text-xs text-slate-600 sm:grid-cols-2 lg:w-1/3">
                    <div class="rounded-2xl bg-white/90 p-4 shadow ring-1 ring-emerald-100">
                        <p class="text-sm font-semibold text-emerald-600">Filters included</p>
                        <p class="mt-2">Gluten-free, Pegan, high-protein, plant-forward, and more.</p>
                    </div>
                    <div class="rounded-2xl bg-white/90 p-4 shadow ring-1 ring-emerald-100">
                        <p class="text-sm font-semibold text-emerald-600">Mobile friendly</p>
                        <p class="mt-2">Save recipes on the go and queue them in your meal planner instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white/80">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16 lg:px-8">
            <!-- Filter Section -->
            <div class="mb-10 rounded-3xl bg-emerald-50/80 p-6 shadow ring-1 ring-emerald-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Filter recipes</p>
                        <h2 class="text-xl font-semibold text-emerald-900">Find recipes by dietary needs</h2>
                    </div>
                    @if(!empty($selectedTags))
                        <a href="{{ route('recipes.catalog') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-900">
                            Clear filters
                        </a>
                    @endif
                </div>
                
                <form method="GET" action="{{ route('recipes.catalog') }}" id="filterForm" class="mt-6">
                    <div class="flex flex-wrap gap-3">
                        @foreach($allTags as $tag)
                            <label class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold shadow-sm ring-1 ring-emerald-100 cursor-pointer transition hover:bg-emerald-50 {{ in_array($tag->id, $selectedTags) ? 'bg-emerald-100 text-emerald-900 ring-emerald-300' : 'text-emerald-700' }}">
                                <input 
                                    type="checkbox" 
                                    name="tags[]" 
                                    value="{{ $tag->id }}"
                                    {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()"
                                    class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500"
                                >
                                <span>{{ $tag->icon ?? '' }} {{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Featured by PrepToEat</p>
                    <h2 class="text-2xl font-bold text-slate-900">Seasonal favorites inspired by Dr. Hyman</h2>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-emerald-700">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-700">
                        <i class="fa-solid fa-leaf"></i>
                        Anti-inflammatory
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-700">
                        <i class="fa-solid fa-fire"></i>
                        High-protein
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-700">
                        <i class="fa-solid fa-carrot"></i>
                        Plant-forward
                    </span>
                </div>
            </div>

            <div class="mt-10 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach($featuredRecipes as $recipe)
                    <article class="flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow-xl ring-1 ring-emerald-100">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="h-full w-full object-cover transition duration-700 hover:scale-110" loading="lazy">
                            <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-emerald-700 shadow">{{ $recipe['category'] }}</span>
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="text-xl font-semibold text-slate-900">{{ $recipe['title'] }}</h3>
                            <p class="mt-3 text-sm text-slate-600">{{ $recipe['summary'] }}</p>
                            <div class="mt-4 flex flex-wrap gap-2 text-xs text-emerald-700">
                                @foreach($recipe['tags'] as $tag)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 font-semibold">{{ $tag }}</span>
                                @endforeach
                            </div>
                            <div class="mt-6 flex flex-col gap-3 text-sm sm:flex-row">
                                <a href="{{ $recipe['url'] }}" target="_blank" rel="noopener" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-white px-4 py-2 font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                    View source
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <form action="{{ route('recipe.generate') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="recipe_link" value="{{ $recipe['url'] }}">
                                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2 font-semibold text-white transition hover:bg-emerald-700">
                                        Import to PrepToEat
                                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50/80">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Community spotlight</p>
                    <h2 class="text-2xl font-bold text-slate-900">Shared from My Kitchen</h2>
                    <p class="mt-2 text-sm text-slate-600">Explore the latest creations from PrepToEat members. Save them to your own kitchen or schedule them in your meal planner.</p>
                </div>
                <div class="flex flex-wrap gap-3 text-xs text-emerald-700">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 font-semibold shadow">Home cooks</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 font-semibold shadow">Nutritionists</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 font-semibold shadow">Diet-friendly</span>
                </div>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($communityRecipes as $communityRecipe)
                    <article class="flex h-full flex-col justify-between rounded-3xl bg-white p-6 shadow-lg ring-1 ring-emerald-100">
                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">{{ $communityRecipe->title }}</h3>
                                <span class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Cookbook</span>
                            </div>
                            <p class="mt-3 text-sm text-slate-600">{{ $communityRecipe->summary ?: 'A PrepToEat member recipe ready for your kitchen.' }}</p>
                            @if($communityRecipe->ingredients)
                                <div class="mt-4 rounded-2xl bg-emerald-50/70 p-4 text-xs text-slate-600">
                                    <p class="font-semibold text-emerald-700">Key ingredients</p>
                                    <p class="mt-2 leading-relaxed">{{ \Illuminate\Support\Str::limit($communityRecipe->ingredients, 160) }}</p>
                                </div>
                            @endif
                            @if($communityRecipe->tags->isNotEmpty())
                                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                                    @foreach($communityRecipe->tags as $tag)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                                            {{ $tag->icon ?? '' }} {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="mt-6 flex flex-col gap-3 text-sm sm:flex-row">
                            @auth
                                <a href="{{ route('recipes.index') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2 font-semibold text-white transition hover:bg-emerald-700">
                                    Save from My Kitchen
                                    <i class="fa-solid fa-bookmark"></i>
                                </a>
                                <a href="{{ route('meal-plan.index') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-white px-4 py-2 font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                    Add to planner
                                    <i class="fa-solid fa-calendar-plus"></i>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2 font-semibold text-white transition hover:bg-emerald-700">
                                    Log in to save
                                </a>
                            @endauth
                        </div>
                        <p class="mt-4 text-xs text-slate-400">Added {{ $communityRecipe->created_at?->diffForHumans() ?? 'recently' }}</p>
                    </article>
                @empty
                    <div class="rounded-3xl bg-white p-8 text-center shadow ring-1 ring-emerald-100 md:col-span-2 xl:col-span-3">
                        <i class="fa-solid fa-books text-3xl text-emerald-400"></i>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">No community recipes yet</h3>
                        <p class="mt-2 text-sm text-slate-600">Be the first to save a recipe and share it with the PrepToEat community.</p>
                        <div class="mt-4 flex justify-center">
                            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Generate a recipe
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl rounded-3xl bg-emerald-900 px-8 py-12 text-emerald-50 shadow-2xl sm:px-12 lg:px-16">
            <div class="grid gap-10 lg:grid-cols-[2fr,1fr] lg:items-center">
                <div>
                    <h2 class="text-2xl font-bold">Turn inspiration into action</h2>
                    <p class="mt-3 text-sm text-emerald-100">Use PrepToEat to capture recipes, customize them for your nutrition goals, and keep your kitchen intentional. Weekly and monthly planners, smart grocery lists, and AI coaching are included.</p>
                    <ul class="mt-6 grid gap-3 text-sm text-emerald-100 sm:grid-cols-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-300"></i> Unlimited saved recipes in My Kitchen</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-300"></i> Calendar planning with reminders</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-300"></i> AI-powered substitutions and tips</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-300"></i> Shareable, print-ready recipe cards</li>
                    </ul>
                </div>
                <div class="space-y-3 text-sm">
                    <a href="{{ url('/') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-white px-5 py-3 font-semibold text-emerald-800 transition hover:bg-emerald-100">
                        Try the recipe extractor
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </a>
                    @auth
                        <a href="{{ route('recipes.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-500">
                            Visit My Kitchen
                            <i class="fa-solid fa-book-open"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-500">
                            Create a free account
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
