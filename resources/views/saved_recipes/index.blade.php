@extends('layouts.site')

@section('title', 'My Saved Recipes | PrepToEat')

@section('content')
<div class="content-shell">
    <div class="page-header">
        <h1 class="page-title">My Saved Recipes</h1>
        <p class="page-subtitle">Your personalized cookbook lives here. Edit, organize, and revisit your favourite dishes anytime.</p>
    </div>

    <div class="card" style="display:inline-block; margin-bottom:2rem;">
        <a class="muted-link" href="{{ url('/') }}">&larr; Back to recipe creator</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($recipes->isEmpty())
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h10.5" />
                </svg>
                <div>No recipes saved yet. Generate one on the homepage and press <strong>Save Recipe</strong> to build your collection.</div>
            </div>
        </div>
    @else
        @php
            $availableCategories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snack', 'Appetizer', 'Beverage'];
            $editingId = old('editing_id');
        @endphp
        <div class="recipe-grid">
            @foreach($recipes as $recipe)
                @php
                    $isEditingThis = (string)$editingId === (string)$recipe->id;
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

                    $ingredientLines = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) ($recipe->ingredients ?? '')))));
                    $instructionSteps = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) ($recipe->instructions ?? '')))));
                    $summaryText = trim((string) ($recipe->summary ?? ''));
                @endphp
                <article class="card recipe-card">
                    <header>
                        <h2 class="recipe-title">{{ $recipe->title }}</h2>
                        <div class="recipe-meta">
                            <span class="recipe-tag">{{ $recipe->category ?? 'Uncategorized' }}</span>
                            @if($recipe->created_at)
                                <span>&bull;</span>
                                <span>Saved {{ $recipe->created_at->format('M j, Y') }}</span>
                            @endif
                        </div>
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

                    <div class="card-actions">
                        <button type="button" class="btn btn-secondary btn-small" data-edit-toggle="edit-form-{{ $recipe->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.688-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487zm0 0L19.5 7.125" />
                            </svg>
                            Edit
                        </button>
                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Delete this recipe?');">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>

                    <div class="edit-panel" id="edit-form-{{ $recipe->id }}" style="{{ $isEditingThis ? 'display:block;' : '' }}">
                        <form action="{{ route('recipes.update', $recipe->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="editing_id" value="{{ $recipe->id }}">
                            <div class="form-group">
                                <label for="title_{{ $recipe->id }}">Title</label>
                                <input type="text" id="title_{{ $recipe->id }}" name="title" class="input-control" value="{{ $titleValue }}">
                                @if($isEditingThis && $errors->has('title'))
                                    <div class="field-error">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="category_{{ $recipe->id }}">Category</label>
                                <select id="category_{{ $recipe->id }}" name="category" class="category-select">
                                    <option value="">Select a category</option>
                                    @foreach($availableCategories as $categoryOption)
                                        <option value="{{ $categoryOption }}" {{ $currentCategory === $categoryOption ? 'selected' : '' }}>{{ $categoryOption }}</option>
                                    @endforeach
                                    <option value="other" {{ $currentCategory === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @if($isEditingThis && $errors->has('category'))
                                    <div class="field-error">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                            <div class="form-group" id="custom_category_group_{{ $recipe->id }}" style="{{ ($isEditingThis && old('category') === 'other') || $isCustomCategory ? 'display:block;' : 'display:none;' }}">
                                <label for="custom_category_{{ $recipe->id }}">Custom Category</label>
                                <input type="text" id="custom_category_{{ $recipe->id }}" name="custom_category" class="input-control" value="{{ $isEditingThis ? old('custom_category', $customCategoryValue) : $customCategoryValue }}">
                                @if($isEditingThis && $errors->has('custom_category'))
                                    <div class="field-error">{{ $errors->first('custom_category') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="summary_{{ $recipe->id }}">Personal Notes</label>
                                <textarea id="summary_{{ $recipe->id }}" name="summary">{{ $notesValue }}</textarea>
                                @if($isEditingThis && $errors->has('summary'))
                                    <div class="field-error">{{ $errors->first('summary') }}</div>
                                @endif
                            </div>
                            <div class="card-actions">
                                <button type="submit" class="btn btn-success btn-small">Save Changes</button>
                                <button type="button" class="btn btn-secondary btn-small" data-close-panel="edit-form-{{ $recipe->id }}">Cancel</button>
                            </div>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-edit-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-edit-toggle');
                const panel = document.getElementById(targetId);
                if (!panel) return;
                const isOpen = panel.style.display === 'block';
                panel.style.display = isOpen ? 'none' : 'block';
            });
        });

        document.querySelectorAll('[data-close-panel]').forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-close-panel');
                const panel = document.getElementById(targetId);
                if (!panel) return;
                panel.style.display = 'none';
            });
        });

        function setupCategorySelect(select) {
            const recipeId = select.getAttribute('id').replace('category_', '');
            const customGroup = document.getElementById('custom_category_group_' + recipeId);
            if (!customGroup) return;
            const customInput = customGroup.querySelector('input');

            function toggleCustom(value) {
                if (value === 'other') {
                    customGroup.style.display = 'block';
                    if (customInput) {
                        customInput.required = true;
                    }
                } else {
                    customGroup.style.display = 'none';
                    if (customInput) {
                        customInput.required = false;
                    }
                }
            }

            toggleCustom(select.value);
            select.addEventListener('change', function () {
                toggleCustom(this.value);
            });
        }

        document.querySelectorAll('.category-select').forEach(setupCategorySelect);
    });
</script>
@endpush
