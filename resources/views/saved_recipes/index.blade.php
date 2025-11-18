@extends('layouts.app')

@php
    $availableCategories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snack', 'Appetizer', 'Beverage'];
    $editingId = old('editing_id');
    $recipeCount = $recipes->count();
    $upcomingCount = isset($upcomingPlans) ? $upcomingPlans->count() : 0;
    $latestSaved = optional($recipes->first())->created_at?->diffForHumans();
    $allTags = \App\Models\RecipeTag::orderBy('name')->get();
@endphp

@section('content')
    <x-page-hero
        eyebrow="My Kitchen"
        title="Your personal cookbook & pantry command center"
        description="Organize every saved recipe, keep personal notes, share with friends, and schedule meals in just a few clicks."
    >
        <x-slot:actions>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-700">
                <i class="fa-solid fa-house"></i>
                Generate a new recipe
            </a>
            <a href="{{ route('meal-plan.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-700 shadow ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                <i class="fa-solid fa-calendar-plus"></i>
                Open meal planner
            </a>
        </x-slot:actions>

        <div class="rounded-3xl bg-white/90 p-6 shadow-xl ring-1 ring-emerald-100/80">
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Kitchen stats</p>
            <dl class="mt-4 grid gap-4 text-sm text-slate-600 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Saved recipes</dt>
                    <dd class="mt-2 text-2xl font-bold text-slate-900">{{ $recipeCount }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Meals scheduled</dt>
                    <dd class="mt-2 text-2xl font-bold text-slate-900">{{ $upcomingCount }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Last update</dt>
                    <dd class="mt-2 text-base font-semibold text-slate-900">{{ $latestSaved ?? 'Just getting started' }}</dd>
                </div>
            </dl>
        </div>
    </x-page-hero>

    <section class="bg-white/80">
        <div class="mx-auto max-w-7xl space-y-10 px-4 py-10 sm:px-6 lg:py-16 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-900 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(isset($upcomingPlans) && $upcomingPlans->isNotEmpty())
                <div class="rounded-3xl bg-emerald-50/80 p-6 shadow ring-1 ring-emerald-100">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Coming up this week</p>
                            <h2 class="text-xl font-semibold text-emerald-900">Meals on your calendar</h2>
                        </div>
                        <a href="{{ route('meal-plan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100">
                            View planner
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                    <ul class="mt-6 space-y-4 text-sm text-slate-700">
                        @foreach($upcomingPlans as $plan)
                            <li class="flex flex-col gap-1 rounded-2xl bg-white/80 px-4 py-3 shadow-sm ring-1 ring-emerald-100 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $plan->planned_for->format('D, M j') }}
                                        @if($plan->meal_type)
                                            • {{ $plan->meal_type }}
                                        @endif
                                    </p>
                                    <a href="{{ route('recipes.index') }}#recipe-{{ $plan->saved_recipe_id }}" class="text-emerald-700 hover:text-emerald-900">
                                        {{ $plan->recipe?->title ?? 'Recipe removed' }}
                                    </a>
                                </div>
                                @if($plan->notes)
                                    <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($plan->notes, 80) }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($recipes->isEmpty())
                <div class="rounded-3xl bg-white p-10 text-center shadow-xl ring-1 ring-emerald-100">
                    <i class="fa-solid fa-bowl-food text-4xl text-emerald-400"></i>
                    <h2 class="mt-4 text-2xl font-semibold text-slate-900">No recipes saved yet</h2>
                    <p class="mt-3 text-sm text-slate-600">Import a recipe from the homepage or browse the curated library to start building your kitchen.</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            Generate a recipe
                        </a>
                        <a href="{{ route('recipes.catalog') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                            Browse curated recipes
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($recipes as $recipe)
                        @php
                            $isEditingThis = (string) $editingId === (string) $recipe->id;
                            $categoryFromOld = $isEditingThis ? old('category') : null;
                            $currentCategory = $categoryFromOld !== null ? $categoryFromOld : ($recipe->category ?? '');
                            $isCustomCategory = false;
                            $customCategoryValue = '';

                            if ($categoryFromOld !== null) {
                                if ($categoryFromOld === 'other') {
                                    $isCustomCategory = true;
                                    $customCategoryValue = old('custom_category', '');
                                }
                            } elseif ($recipe->category && !in_array($recipe->category, $availableCategories)) {
                                $isCustomCategory = true;
                                $currentCategory = 'other';
                                $customCategoryValue = $recipe->category;
                            }

                            $titleValue = $isEditingThis ? old('title', $recipe->title) : $recipe->title;
                            $notesValue = $isEditingThis ? old('summary', $recipe->summary) : $recipe->summary;
                        @endphp
                        <article id="recipe-{{ $recipe->id }}" class="rounded-3xl bg-white/90 p-6 shadow-2xl ring-1 ring-emerald-100">
                            <div class="flex flex-col gap-3 border-b border-emerald-50 pb-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Saved recipe</p>
                                    <h2 class="text-2xl font-semibold text-slate-900">{{ $recipe->title }}</h2>
                                </div>
                                <div class="flex flex-wrap gap-3 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                                        <i class="fa-solid fa-tag"></i>
                                        {{ $recipe->category ?? 'Uncategorized' }}
                                    </span>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 font-semibold shadow-inner ring-1 ring-slate-100">
                                        <i class="fa-regular fa-clock"></i>
                                        Saved {{ $recipe->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-emerald-600">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                        Ingredients
                                    </div>
                                    <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-slate-700">
                                        @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                                            @if(trim($ingredient) !== '')
                                                <li>{{ $ingredient }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-emerald-600">
                                        <i class="fa-solid fa-list-check"></i>
                                        Instructions
                                    </div>
                                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-slate-700">
                                        @foreach(explode("\n", $recipe->instructions) as $step)
                                            @if(trim($step) !== '')
                                                <li>{{ $step }}</li>
                                            @endif
                                        @endforeach
                                    </ol>
                                </div>
                            </div>

                            @if($recipe->summary)
                                <div class="mt-6 rounded-2xl bg-emerald-50/70 p-4 text-sm text-slate-700">
                                    <div class="flex items-center gap-2 font-semibold text-emerald-700">
                                        <i class="fa-solid fa-lightbulb"></i>
                                        Personal notes
                                    </div>
                                    <p class="mt-2">{{ $recipe->summary }}</p>
                                </div>
                            @endif

                            @if($recipe->tags->isNotEmpty())
                                <div class="mt-6 flex flex-wrap gap-2 text-xs">
                                    @foreach($recipe->tags as $tag)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                                            {{ $tag->icon ?? '' }} {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-6 flex flex-wrap gap-3 text-sm">
                                <button type="button" data-panel-toggle="edit-panel-{{ $recipe->id }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 font-semibold text-white transition hover:bg-emerald-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit details
                                </button>
                                <button type="button" data-panel-toggle="plan-panel-{{ $recipe->id }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                    Add to planner
                                </button>
                                <button type="button" data-panel-toggle="share-panel-{{ $recipe->id }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                    <i class="fa-solid fa-share-nodes"></i>
                                    Share
                                </button>
                                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-700">
                                        <i class="fa-solid fa-trash-can"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <div id="plan-panel-{{ $recipe->id }}" class="mt-6 hidden rounded-2xl bg-emerald-50/60 p-5 shadow-inner">
                                <form action="{{ route('meal-plan.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="saved_recipe_id" value="{{ $recipe->id }}">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label for="planned_for_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Date</label>
                                            <input type="date" id="planned_for_{{ $recipe->id }}" name="planned_for" required class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                        </div>
                                        <div>
                                            <label for="meal_type_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Meal slot</label>
                                            <select id="meal_type_{{ $recipe->id }}" name="meal_type" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                                <option value="">Anytime</option>
                                                <option value="Breakfast">Breakfast</option>
                                                <option value="Lunch">Lunch</option>
                                                <option value="Dinner">Dinner</option>
                                                <option value="Snack">Snack</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="notes_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Notes</label>
                                        <textarea id="notes_{{ $recipe->id }}" name="notes" rows="3" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100" placeholder="Add prep reminders or grocery notes..."></textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        Schedule meal
                                    </button>
                                </form>
                            </div>

                            <div id="share-panel-{{ $recipe->id }}" class="mt-6 hidden rounded-2xl bg-white/80 p-5 shadow-inner ring-1 ring-emerald-100">
                                <form action="{{ route('recipes.share.store', $recipe) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="expires_at_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Link expiration (optional)</label>
                                        <input type="datetime-local" id="expires_at_{{ $recipe->id }}" name="expires_at" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                    </div>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        Generate share link
                                    </button>
                                    <p class="text-xs text-slate-500">Shared recipes are read-only for your friends. Expired links automatically stop working.</p>
                                </form>

                                @if($recipe->shares->isNotEmpty())
                                    <div class="mt-6 space-y-4">
                                        <p class="text-sm font-semibold text-slate-700">Active links</p>
                                        @foreach($recipe->shares as $share)
                                            @php
                                                $shareUrl = route('recipes.share.show', $share->token);
                                            @endphp
                                            <div class="rounded-2xl bg-emerald-50/70 p-4 text-sm shadow-sm ring-1 ring-emerald-100">
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                                    <a class="break-words font-semibold text-emerald-700 hover:text-emerald-900" href="{{ $shareUrl }}" target="_blank" rel="noopener">
                                                        {{ $shareUrl }}
                                                    </a>
                                                    <form action="{{ route('recipes.share.destroy', $share) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 transition hover:bg-red-200">
                                                            <i class="fa-solid fa-circle-xmark"></i>
                                                            Disable
                                                        </button>
                                                    </form>
                                                </div>
                                                <p class="text-xs text-slate-500">
                                                    Created {{ $share->created_at->diffForHumans() }}
                                                    @if($share->expires_at)
                                                        • Expires {{ $share->expires_at->diffForHumans() }}
                                                    @else
                                                        • No expiration
                                                    @endif
                                                </p>
                                                <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                                                    <a href="https://twitter.com/intent/tweet?text={{ urlencode('Check out this recipe: '.$shareUrl) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-emerald-700 shadow ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                                        <i class="fa-brands fa-x-twitter"></i>
                                                        Share
                                                    </a>
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-emerald-700 shadow ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                                        <i class="fa-brands fa-facebook"></i>
                                                        Post
                                                    </a>
                                                    <a href="mailto:?subject={{ rawurlencode('Recipe from PrepToEat') }}&body={{ rawurlencode('Thought you might like this recipe: '.$shareUrl) }}" class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-emerald-700 shadow ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                                                        <i class="fa-solid fa-envelope"></i>
                                                        Email
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div id="edit-panel-{{ $recipe->id }}" class="mt-6 {{ $isEditingThis ? '' : 'hidden' }} rounded-2xl bg-white/80 p-5 shadow-inner ring-1 ring-emerald-100">
                                <form action="{{ route('recipes.update', $recipe->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="editing_id" value="{{ $recipe->id }}">
                                    <div>
                                        <label for="title_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Title</label>
                                        <input type="text" id="title_{{ $recipe->id }}" name="title" value="{{ $titleValue }}" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                        @if($isEditingThis && $errors->has('title'))
                                            <p class="mt-1 text-xs text-red-500">{{ $errors->first('title') }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="category_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Category</label>
                                        <select id="category_{{ $recipe->id }}" name="category" data-category-select="{{ $recipe->id }}" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                            <option value="">Select a category</option>
                                            @foreach($availableCategories as $categoryOption)
                                                <option value="{{ $categoryOption }}" {{ $currentCategory === $categoryOption ? 'selected' : '' }}>{{ $categoryOption }}</option>
                                            @endforeach
                                            <option value="other" {{ $currentCategory === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @if($isEditingThis && $errors->has('category'))
                                            <p class="mt-1 text-xs text-red-500">{{ $errors->first('category') }}</p>
                                        @endif
                                    </div>
                                    <div id="custom-category-{{ $recipe->id }}" class="{{ ($isEditingThis && old('category') === 'other') || $isCustomCategory ? '' : 'hidden' }}">
                                        <label for="custom_category_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Custom category</label>
                                        <input type="text" id="custom_category_{{ $recipe->id }}" name="custom_category" value="{{ $isEditingThis ? old('custom_category', $customCategoryValue) : $customCategoryValue }}" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                        @if($isEditingThis && $errors->has('custom_category'))
                                            <p class="mt-1 text-xs text-red-500">{{ $errors->first('custom_category') }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="summary_{{ $recipe->id }}" class="text-sm font-semibold text-slate-700">Personal notes</label>
                                        <textarea id="summary_{{ $recipe->id }}" name="summary" rows="3" class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ $notesValue }}</textarea>
                                        @if($isEditingThis && $errors->has('summary'))
                                            <p class="mt-1 text-xs text-red-500">{{ $errors->first('summary') }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-slate-700">Dietary Tags (optional)</label>
                                        <div class="mt-2 flex flex-wrap gap-3">
                                            @foreach($allTags as $tag)
                                                <label class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-xs font-semibold shadow-sm ring-1 ring-emerald-100 cursor-pointer transition hover:bg-emerald-50">
                                                    <input 
                                                        type="checkbox" 
                                                        name="tags[]" 
                                                        value="{{ $tag->id }}"
                                                        {{ $recipe->tags->contains($tag->id) ? 'checked' : '' }}
                                                        class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500"
                                                    >
                                                    <span>{{ $tag->icon ?? '' }} {{ $tag->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-2 text-xs text-slate-500">Select tags that match this recipe's dietary characteristics.</p>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                            Save changes
                                        </button>
                                        <button type="button" data-panel-close="edit-panel-{{ $recipe->id }}" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            <div class="rounded-3xl bg-slate-900 px-6 py-8 text-slate-100 shadow-2xl">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-300">AI cooking coach</p>
                        <h2 class="text-2xl font-semibold text-white">Ask for substitutions, prep tips, and more</h2>
                    </div>
                    <i class="fa-solid fa-message text-3xl text-emerald-300"></i>
                </div>
                <div id="recipeChatLog" class="mt-6 max-h-64 space-y-4 overflow-y-auto rounded-2xl bg-white/10 px-4 py-3 text-sm text-white" aria-live="polite"></div>
                <form id="recipeChatForm" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="chatMessage" class="text-sm font-semibold text-emerald-200">Your question</label>
                        <textarea id="chatMessage" name="message" rows="3" class="mt-2 w-full rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/60 focus:border-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                    </div>
                    <div>
                        <label for="chatIngredients" class="text-sm font-semibold text-emerald-200">Ingredients on hand (optional)</label>
                        <input id="chatIngredients" name="ingredients" type="text" class="mt-2 w-full rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/60 focus:border-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Carrots, chicken thighs, quinoa...">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-emerald-400">
                        Ask PrepToEat
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-panel-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-panel-toggle');
            const panel = document.getElementById(targetId);
            if (!panel) return;
            panel.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('[data-panel-close]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-panel-close');
            const panel = document.getElementById(targetId);
            if (!panel) return;
            panel.classList.add('hidden');
        });
    });

    document.querySelectorAll('[data-category-select]').forEach((select) => {
        const recipeId = select.getAttribute('data-category-select');
        const toggleCustom = () => {
            const customGroup = document.getElementById('custom-category-' + recipeId);
            if (!customGroup) return;
            if (select.value === 'other') {
                customGroup.classList.remove('hidden');
            } else {
                customGroup.classList.add('hidden');
            }
        };

        select.addEventListener('change', toggleCustom);
        toggleCustom();
    });

    const chatForm = document.getElementById('recipeChatForm');
    const chatLog = document.getElementById('recipeChatLog');

    if (chatForm && chatLog) {
        chatForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(chatForm);
            const message = (formData.get('message') || '').toString().trim();
            const ingredients = (formData.get('ingredients') || '').toString().trim();
            if (!message) {
                return;
            }

            appendChat('You', message);
            chatForm.reset();

            try {
                const response = await fetch(@json(route('chat.respond')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': chatForm.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({ message, ingredients }),
                });

                if (!response.ok) {
                    throw new Error('Bad response');
                }

                const data = await response.json();
                appendChat('PrepToEat', data.reply || "I'm not sure how to help with that.");
            } catch (error) {
                appendChat('PrepToEat', 'Something went wrong reaching the assistant. Try again shortly.');
            }
        });
    }

    function appendChat(author, text) {
        if (!chatLog) {
            return;
        }
        const wrapper = document.createElement('div');
        wrapper.className = 'space-y-1 rounded-2xl bg-white/5 p-3';

        const title = document.createElement('p');
        title.className = 'text-xs font-semibold uppercase tracking-widest text-emerald-200';
        title.textContent = author;

        const body = document.createElement('p');
        body.className = 'text-sm text-white/90';
        body.textContent = text;

        wrapper.appendChild(title);
        wrapper.appendChild(body);
        chatLog.appendChild(wrapper);
        chatLog.scrollTop = chatLog.scrollHeight;
    }
});
</script>
@endpush

