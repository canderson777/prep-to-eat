@extends('layouts.site')

@section('title', 'PrepToEat · AI Recipe Assistant')

@section('content')
<div class="content-shell">
    <div class="page-header">
        <h1 class="page-title">AI-crafted recipes, ready for dinner tonight</h1>
        <p class="page-subtitle">Paste any recipe URL or ingredient list and let PrepToEat turn it into a beautifully organized set of ingredients, instructions, and personal notes you can save for later.</p>
        @guest
            <div class="stat-pill" style="margin-top: 1.25rem;">
                <span>Local saves used:</span>
                <strong id="local-count">0</strong>
                <span>/ 4</span>
            </div>
        @endguest
    </div>

    @auth
        <div id="importBanner" class="notice-banner">
            We found <strong><span id="importCount">0</span></strong> recipes saved locally.
            <button id="importBtn" class="btn btn-success btn-small" type="button">Import to my account</button>
            <button id="dismissImport" class="btn btn-secondary btn-small" type="button">Not now</button>
        </div>
    @endauth

    <div class="two-column">
        <div class="card">
            <h2 style="margin-top:0; font-size:1.4rem; margin-bottom:1.1rem;">Tell us what you'd like to cook</h2>
            <form method="POST" action="{{ url('/recipe') }}" id="recipeForm" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="form-group">
                    <label for="recipe_link">Paste your recipe link or text</label>
                    <textarea name="recipe_link" id="recipe_link" rows="6" placeholder="Paste recipe URL or text here..." required></textarea>
                </div>
                <div class="card-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h13.5M3 9h9M3 13.5h9M3 18h13.5M16.5 15L21 10.5l-4.5-4.5" />
                        </svg>
                        Get Recipe
                    </button>
                    <button type="button" class="btn btn-secondary" id="refreshBtn">Refresh</button>
                </div>
                <div id="spinner" class="loading-indicator" role="status" aria-live="polite">
                    <div class="spinner"></div>
                    <span>Fetching recipe&hellip;</span>
                </div>
            </form>
        </div>

        <div class="card">
            @php
                $recipe = session('recipe');
                $title = session('title');
                $ingredients = session('ingredients');
                $instructions = session('instructions');
                $summary = session('summary');

                $ingredientLines = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) ($ingredients ?? '')))));
                $instructionSteps = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) ($instructions ?? '')))));
                $summaryText = trim((string) ($summary ?? ''));
            @endphp

            @if($recipe && ($title || count($ingredientLines) || count($instructionSteps)))
                <header style="margin-bottom: 1.25rem;">
                    <p class="badge" style="margin-bottom:0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25c0-.621.504-1.125 1.125-1.125h15.75c.621 0 1.125.504 1.125 1.125v7.5c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 013 15.75v-7.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6V4.875A1.125 1.125 0 017.125 3.75h9.75A1.125 1.125 0 0118 4.875V6" />
                        </svg>
                        AI Recipe Result
                    </p>
                    @if($title)
                        <h2 class="recipe-title" style="font-size:1.6rem;">{{ $title }}</h2>
                    @endif
                </header>

                <section class="recipe-section">
                    <div class="section-title">
                        <span class="icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4.5h-3a2.25 2.25 0 00-2.25 2.25v3m5.25-5.25h9m0 0h3a2.25 2.25 0 012.25 2.25v3m-5.25-5.25v15m0 0h-9m9 0h3a2.25 2.25 0 002.25-2.25v-3m-14.25 5.25h-3A2.25 2.25 0 012.25 18v-3m0-6v9" />
                            </svg>
                        </span>
                        Ingredients
                    </div>
                    <ul>
                        @foreach($ingredientLines as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </section>

                <section class="recipe-section">
                    <div class="section-title">
                        <span class="icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487zm0 0L19.5 7.125" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 13.5V21" />
                            </svg>
                        </span>
                        Instructions
                    </div>
                    <ol>
                        @foreach($instructionSteps as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </section>

                @if($summaryText !== '')
                    <section class="recipe-section">
                        <div class="section-title">
                            <span class="icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9.75L9 12l2.25 2.25m3-4.5L12 12l2.25 2.25M3.75 6h16.5M3.75 9.75h16.5M3.75 13.5h16.5M3.75 17.25h16.5" />
                                </svg>
                            </span>
                            Personal Notes
                        </div>
                        <div class="note-box">{{ $summaryText }}</div>
                    </section>
                @endif

                @auth
                    <form id="saveRecipeForm" action="{{ route('recipes.save') }}" method="POST" enctype="multipart/form-data" style="margin-top: 1.5rem;">
                        @csrf
                        <input type="hidden" name="title" value="{{ $title ?? '' }}">
                        <input type="hidden" name="ingredients" value="{{ $ingredients ?? '' }}">
                        <input type="hidden" name="instructions" value="{{ $instructions ?? '' }}">
                        <input type="hidden" name="summary" value="{{ $summary ?? '' }}">
                        <div class="form-group" style="margin-bottom:1.25rem;">
                            <label for="category"><strong>Category</strong></label>
                            <select name="category" id="category">
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
                            <input type="text" name="custom_category" id="custom_category" placeholder="Or enter a custom category" class="input-control" style="margin-top:0.75rem; display:none;">
                            @error('category')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                            @error('custom_category')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="card-actions">
                            <button type="submit" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Save Recipe
                            </button>
                        </div>
                    </form>
                @else
                    <div style="margin-top: 1.75rem;">
                        <div class="form-group" style="margin-bottom:1.25rem;">
                            <label for="guest_category"><strong>Category</strong></label>
                            <select id="guest_category">
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
                            <input type="text" id="guest_custom_category" placeholder="Or enter a custom category" class="input-control" style="margin-top:0.75rem; display:none;">
                        </div>
                        <button type="button" class="btn btn-success" id="guestSaveBtn">Save locally</button>
                        <div id="guestSaveMsg" style="display:none; color:#166534; font-weight:600; margin-top:0.65rem;">Saved to your browser.</div>
                        <p style="margin-top:1.25rem; color: var(--text-muted); font-size:0.95rem;">
                            Create an account to sync your saved recipes later:
                            <a class="muted-link" href="{{ route('register') }}">Register</a> or
                            <a class="muted-link" href="{{ route('login') }}">Login</a>.
                        </p>
                    </div>
                @endauth
            @else
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75l7.5-3 7.5 3m-15 0l7.5 3 7.5-3m-15 0V17.25a2.25 2.25 0 001.344 2.07l6.375 2.85c.52.232 1.098.232 1.618 0l6.375-2.85A2.25 2.25 0 0019.5 17.25V6.75m-15 0L12 9.75" />
                    </svg>
                    <div>
                        Paste a recipe link or describe your ingredients to get started. We'll organize everything for you.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recipeForm = document.getElementById('recipeForm');
        const spinner = document.getElementById('spinner');
        const refreshBtn = document.getElementById('refreshBtn');

        if (recipeForm && spinner) {
            recipeForm.addEventListener('submit', function () {
                spinner.classList.add('is-visible');
            });
        }

        if (refreshBtn) {
            refreshBtn.addEventListener('click', function () {
                window.location.href = '/?clear=1';
            });
        }

        @auth
        (function () {
            const KEY = 'guestRecipes';
            const SEEN = 'import_banner_dismissed';

            function getGuestRecipes(){ try{return JSON.parse(localStorage.getItem(KEY))||[]}catch{return[]}}
            const list = getGuestRecipes();
            if (!list.length || localStorage.getItem(SEEN)==='1') return;

            const b=document.getElementById('importBanner');
            const c=document.getElementById('importCount');
            const btn=document.getElementById('importBtn');
            const dis=document.getElementById('dismissImport');
            if(!b||!c||!btn||!dis) return;

            c.textContent = list.length;
            b.style.display = 'block';

            btn.addEventListener('click', async () => {
                try {
                    const res = await fetch("{{ route('recipes.import') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ recipes: list })
                    });
                    if (!res.ok) throw new Error('Import failed');

                    localStorage.removeItem(KEY);
                    localStorage.removeItem('free_uses');
                    alert('Imported! Your recipes are now in your account.');
                    window.location.href = "{{ route('recipes.index') }}";
                } catch (e) {
                    alert('Could not import. Please try again.');
                }
            });

            dis.addEventListener('click', () => {
                localStorage.setItem(SEEN, '1');
                b.style.display = 'none';
            });
        })();
        @endauth

        @auth
        const categorySelect = document.getElementById('category');
        const customCategoryInput = document.getElementById('custom_category');
        if (categorySelect && customCategoryInput) {
            categorySelect.addEventListener('change', function () {
                if (this.value === 'other') {
                    customCategoryInput.style.display = 'block';
                    customCategoryInput.required = true;
                    customCategoryInput.focus();
                } else {
                    customCategoryInput.style.display = 'none';
                    customCategoryInput.required = false;
                    customCategoryInput.value = '';
                }
            });
        }
        @endauth

        @guest
        (function () {
            const KEY = 'guestRecipes';
            const LIMIT = 4;
            const saveBtn = document.getElementById('guestSaveBtn');
            const guestCategory = document.getElementById('guest_category');
            const guestCustom = document.getElementById('guest_custom_category');
            const message = document.getElementById('guestSaveMsg');
            const count = document.getElementById('local-count');

            function getGuestRecipes(){ try{return JSON.parse(localStorage.getItem(KEY))||[]}catch{return[]}}
            function setGuestRecipes(list){ localStorage.setItem(KEY, JSON.stringify(list)); }

            function updateCount() {
                if (!count) return;
                const list = getGuestRecipes();
                count.textContent = list.length;
            }

            if (guestCategory && guestCustom) {
                guestCategory.addEventListener('change', function () {
                    if (this.value === 'other') {
                        guestCustom.style.display = 'block';
                        guestCustom.required = true;
                        guestCustom.focus();
                    } else {
                        guestCustom.style.display = 'none';
                        guestCustom.required = false;
                        guestCustom.value = '';
                    }
                });
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', function () {
                    const list = getGuestRecipes();
                    if (list.length >= LIMIT) {
                        alert('Local storage is limited to four recipes. Delete one to save another.');
                        return;
                    }

                    const recipe = {
                        id: 'gr_' + Date.now() + '_' + Math.random().toString(36).slice(2),
                        title: @json($title ?? ''),
                        ingredients: @json($ingredients ?? ''),
                        instructions: @json($instructions ?? ''),
                        summary: @json($summary ?? ''),
                        category: guestCategory ? (guestCategory.value === 'other' ? guestCustom.value : guestCategory.value) : '',
                        savedAt: new Date().toISOString()
                    };

                    if (!recipe.title && !recipe.ingredients && !recipe.instructions) {
                        alert('Generate a recipe before saving.');
                        return;
                    }

                    list.push(recipe);
                    setGuestRecipes(list);
                    updateCount();
                    if (message) {
                        message.style.display = 'block';
                        setTimeout(() => message.style.display = 'none', 2500);
                    }
                });
            }

            updateCount();
        })();
        @endguest
    });
</script>
@endpush
