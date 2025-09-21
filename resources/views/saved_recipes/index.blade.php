<!DOCTYPE html>
<html>
<head>
    <title>My Saved Recipes | PrepToEat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; margin: 0; }
        .container { max-width: 750px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 20px #e3f5ff; padding: 32px 24px 40px 24px; }
        h1 { color: #38b6ff; margin-bottom: 30px; font-size: 2.2em; text-align: center; letter-spacing: 1px; }
        .recipe-card { margin-bottom: 28px; border: 1px solid #e3f5ff; border-radius: 9px; padding: 26px 18px; background: #fafdff; }
        .recipe-title { font-size: 1.45em; font-weight: bold; color: #2c3e50; margin-bottom: 3px; }
        .category { color: #38b6ff; font-size: 1em; margin-bottom: 11px; }
        .section-header { font-weight: bold; color: #007bff; margin: 14px 0 6px 0; display: flex; align-items: center; font-size: 1.1em; }
        .section-header .icon { margin-right: 7px; }
        ul, ol { margin-left: 22px; margin-bottom: 13px; }
        .summary { background: #e3f5ff; border-radius: 6px; padding: 13px 14px; margin-top: 8px; color: #2c3e50; font-size: 1em; }
        .delete-btn { background: #ff5e5e; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; margin-top: 13px; font-size: 0.98em; transition: background .18s; }
        .delete-btn:hover { background: #e20000; }
        .edit-btn { background: #38b6ff; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; font-size: 0.98em; transition: background .18s; margin-right: 10px; }
        .edit-btn:hover { background: #109cff; }
        .action-row { display: flex; align-items: center; margin-top: 18px; gap: 12px; flex-wrap: wrap; }
        .edit-form { margin-top: 18px; background: #f1f7ff; border-radius: 8px; padding: 16px; border: 1px solid #cfe8ff; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-weight: bold; color: #1f2937; margin-bottom: 6px; }
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea { width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.95em; box-sizing: border-box; }
        .form-group textarea { min-height: 90px; resize: vertical; }
        .save-changes-btn { background: #22c55e; color: #fff; border: none; padding: 9px 18px; border-radius: 4px; cursor: pointer; font-size: 0.98em; transition: background .18s; }
        .save-changes-btn:hover { background: #16a34a; }
        .cancel-edit { background: transparent; border: none; color: #64748b; margin-left: 12px; cursor: pointer; font-size: 0.95em; }
        .field-error { color: #dc2626; font-size: 0.85em; margin-top: 4px; }
        .nav-buttons { text-align: center; margin-bottom: 20px; }
        .home-btn {
            background: #38b6ff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background .18s;
        }
        .home-btn:hover { background: #109cff; }
        @media (max-width: 600px) {
            .container { padding: 12px 3vw; }
            .recipe-card { padding: 14px 6vw; }
            .action-row { flex-direction: column; align-items: flex-start; }
            .cancel-edit { margin-left: 0; margin-top: 8px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            <a href="{{ url('/') }}" class="home-btn">Home</a>
        </div>
        <h1>My Saved Recipes</h1>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div style="margin-bottom: 22px; padding:10px 18px; background: #e6ffe7; border-left: 5px solid #28b76b; border-radius: 5px; color:#25754a;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="margin-bottom: 22px; padding:10px 18px; background: #ffe6e6; border-left: 5px solid #ff5e5e; border-radius: 5px; color:#952828;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Recipe List --}}
        @if($recipes->isEmpty())
            <p style="text-align:center; color:#888; font-size:1.1em;">No recipes saved yet. Go cook up something awesome!</p>
        @else
            @php
                $availableCategories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snack', 'Appetizer', 'Beverage'];
                $editingId = old('editing_id');
            @endphp
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
                @endphp
                <div class="recipe-card">
                    <div class="recipe-title">{{ $recipe->title }}</div>
                    <div class="category">
                        <span style="font-size:1.15em;">&#128205;</span> <!-- 📍 -->
                        {{ $recipe->category ?? 'Uncategorized' }}
                    </div>

                    <div class="section-header"><span class="icon">&#129367;</span> Ingredients:</div>
                    <ul>
                        @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                            @if(trim($ingredient) !== '')
                                <li>{{ $ingredient }}</li>
                            @endif
                        @endforeach
                    </ul>

                    <div class="section-header"><span class="icon">&#128221;</span> Instructions:</div>
                    <ol>
                        @foreach(explode("\n", $recipe->instructions) as $step)
                            @if(trim($step) !== '')
                                <li>{{ $step }}</li>
                            @endif
                        @endforeach
                    </ol>

                    @if($recipe->summary)
                        <div class="section-header"><span class="icon">&#128161;</span> Personal Notes:</div>
                        <div class="summary">{{ $recipe->summary }}</div>
                    @endif

                    <div class="action-row">
                        <button type="button" class="edit-btn" data-target="edit-form-{{ $recipe->id }}">Edit</button>
                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Delete Recipe</button>
                        </form>
                    </div>

                    <div class="edit-form" id="edit-form-{{ $recipe->id }}" style="{{ $isEditingThis ? 'display:block;' : 'display:none;' }}">
                        <form action="{{ route('recipes.update', $recipe->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="editing_id" value="{{ $recipe->id }}">
                            <div class="form-group">
                                <label for="title_{{ $recipe->id }}">Title</label>
                                <input type="text" id="title_{{ $recipe->id }}" name="title" value="{{ $titleValue }}">
                                @if($isEditingThis && $errors->has('title'))
                                    <div class="field-error">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="category_{{ $recipe->id }}">Category</label>
                                <select id="category_{{ $recipe->id }}" name="category" class="category-select" data-recipe-id="{{ $recipe->id }}">
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
                                <input type="text" id="custom_category_{{ $recipe->id }}" name="custom_category" value="{{ $isEditingThis ? old('custom_category', $customCategoryValue) : $customCategoryValue }}">
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
                            <button type="submit" class="save-changes-btn">Save Changes</button>
                            <button type="button" class="cancel-edit" data-target="edit-form-{{ $recipe->id }}">Cancel</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script>
        document.querySelectorAll('.edit-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var targetId = this.getAttribute('data-target');
                var form = document.getElementById(targetId);
                if (!form) return;
                form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
            });
        });

        document.querySelectorAll('.cancel-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                var targetId = this.getAttribute('data-target');
                var form = document.getElementById(targetId);
                if (!form) return;
                form.style.display = 'none';
            });
        });

        function setupCategorySelect(select) {
            var recipeId = select.getAttribute('data-recipe-id');
            var customGroup = document.getElementById('custom_category_group_' + recipeId);
            if (!customGroup) return;
            var customInput = customGroup.querySelector('input');

            var toggleCustom = function(value) {
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
            };

            toggleCustom(select.value);

            select.addEventListener('change', function() {
                toggleCustom(this.value);
            });
        }

        document.querySelectorAll('.category-select').forEach(setupCategorySelect);
    </script>
</body>
</html>
