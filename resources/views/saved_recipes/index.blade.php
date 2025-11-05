<!DOCTYPE html>
<html>
<head>
    <title>My Saved Recipes | PrepToEat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; margin: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 20px #e3f5ff; padding: 32px 24px 40px 24px; }
        h1 { color: #38b6ff; margin-bottom: 12px; font-size: 2.2em; text-align: center; letter-spacing: 1px; }
        .nav-buttons { text-align: center; margin-bottom: 24px; display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; }
        .nav-link { background: #38b6ff; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-size: 1em; cursor: pointer; text-decoration: none; display: inline-block; transition: background .18s; }
        .nav-link:hover { background: #109cff; }
        .summary-card { background: #eaf7ff; border-radius: 10px; padding: 18px; margin-bottom: 24px; border: 1px solid #cbe9ff; }
        .summary-card h2 { margin-top: 0; color: #2563eb; font-size: 1.2em; }
        .summary-card ul { padding-left: 18px; margin: 12px 0 0 0; }
        .recipe-card { margin-bottom: 28px; border: 1px solid #e3f5ff; border-radius: 9px; padding: 26px 18px; background: #fafdff; }
        .recipe-title { font-size: 1.45em; font-weight: bold; color: #2c3e50; margin-bottom: 3px; display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .category { color: #38b6ff; font-size: 1em; margin-bottom: 11px; }
        .section-header { font-weight: bold; color: #007bff; margin: 14px 0 6px 0; display: flex; align-items: center; font-size: 1.05em; }
        .section-header .icon { margin-right: 7px; }
        ul, ol { margin-left: 22px; margin-bottom: 13px; }
        .summary { background: #e3f5ff; border-radius: 6px; padding: 13px 14px; margin-top: 8px; color: #2c3e50; font-size: 1em; }
        .action-row { display: flex; align-items: center; margin-top: 18px; gap: 12px; flex-wrap: wrap; }
        button, .button-link { background: #38b6ff; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; font-size: 0.96em; transition: background .18s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        button:hover, .button-link:hover { background: #109cff; }
        .delete-btn { background: #ff5e5e; }
        .delete-btn:hover { background: #e20000; }
        .save-changes-btn { background: #22c55e; }
        .save-changes-btn:hover { background: #16a34a; }
        .form-inline { background: #f1f7ff; border-radius: 8px; padding: 14px; border: 1px solid #cfe8ff; margin-top: 16px; display: none; }
        .form-inline label { display: block; font-weight: bold; color: #1f2937; margin-bottom: 6px; }
        .form-inline input[type="text"], .form-inline input[type="date"], .form-inline input[type="datetime-local"], .form-inline select, .form-inline textarea { width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.95em; box-sizing: border-box; margin-bottom: 12px; }
        .share-links { margin-top: 12px; background: #fff; border: 1px solid #e0f2fe; border-radius: 6px; padding: 10px 12px; }
        .share-links p { margin: 0 0 8px 0; font-size: 0.95em; color: #1f2937; }
        .share-item { display: flex; justify-content: space-between; gap: 10px; align-items: center; margin-bottom: 8px; flex-wrap: wrap; }
        .share-url { font-size: 0.9em; color: #1d4ed8; word-break: break-all; }
        .social-links { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
        .social-links a { background: #0ea5e9; color: #fff; padding: 6px 10px; border-radius: 4px; font-size: 0.85em; text-decoration: none; }
        .social-links a:hover { background: #0284c7; }
        .field-error { color: #dc2626; font-size: 0.85em; margin-top: -8px; margin-bottom: 8px; }
        .chat-panel { margin-top: 40px; border: 1px solid #cbe9ff; border-radius: 10px; padding: 20px; background: #f0f9ff; }
        .chat-panel h2 { margin-top: 0; color: #2563eb; }
        .chat-log { background: #fff; border-radius: 8px; border: 1px solid #dbeafe; padding: 14px; max-height: 260px; overflow-y: auto; margin-bottom: 14px; }
        .chat-entry { margin-bottom: 12px; }
        .chat-entry strong { display: block; color: #1e3a8a; margin-bottom: 4px; }
        .chat-form { display: grid; gap: 10px; }
        .chat-form textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #93c5fd; min-height: 70px; }
        .chat-form input[type="text"] { padding: 8px; border-radius: 6px; border: 1px solid #93c5fd; }
        .chat-submit { justify-self: end; background: #2563eb; padding: 10px 18px; }
        @media (max-width: 640px) {
            .container { margin: 20px auto; padding: 18px 14px 26px 14px; }
            .recipe-card { padding: 18px 12px; }
            .action-row { flex-direction: column; align-items: flex-start; }
            .share-item { flex-direction: column; align-items: flex-start; }
            button, .button-link { width: 100%; justify-content: center; }
            .chat-panel { padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            <a href="{{ url('/') }}" class="nav-link">Home</a>
            <a href="{{ route('meal-plan.index') }}" class="nav-link">Meal Plan</a>
        </div>
        <h1>My Saved Recipes</h1>

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

        @if(isset($upcomingPlans) && $upcomingPlans->isNotEmpty())
            <div class="summary-card">
                <h2>Coming up this week</h2>
                <ul>
                    @foreach($upcomingPlans as $plan)
                        <li>
                            <strong>{{ $plan->planned_for->format('D, M j') }}</strong>
                            @if($plan->meal_type)
                                ({{ $plan->meal_type }})
                            @endif
                            —
                            <a href="{{ route('recipes.index') }}#recipe-{{ $plan->saved_recipe_id }}">{{ $plan->recipe?->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                <div class="recipe-card" id="recipe-{{ $recipe->id }}">
                    <div class="recipe-title">
                        <span>{{ $recipe->title }}</span>
                        <span style="font-size:0.85em; color:#94a3b8;">Saved {{ $recipe->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="category">
                        <span style="font-size:1.15em;">&#128205;</span>
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
                        <button type="button" data-target="calendar-form-{{ $recipe->id }}" class="toggle-inline">Add to Calendar</button>
                        <button type="button" data-target="share-form-{{ $recipe->id }}" class="toggle-inline">Share</button>
                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Delete Recipe</button>
                        </form>
                    </div>

                    <div class="form-inline" id="calendar-form-{{ $recipe->id }}">
                        <form action="{{ route('meal-plan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="saved_recipe_id" value="{{ $recipe->id }}">
                            <label for="planned_for_{{ $recipe->id }}">Date</label>
                            <input type="date" id="planned_for_{{ $recipe->id }}" name="planned_for" required>
                            <label for="meal_type_{{ $recipe->id }}">Meal</label>
                            <select id="meal_type_{{ $recipe->id }}" name="meal_type">
                                <option value="">Select meal slot</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Snack">Snack</option>
                            </select>
                            <label for="notes_{{ $recipe->id }}">Notes</label>
                            <textarea id="notes_{{ $recipe->id }}" name="notes" placeholder="Add prep reminders or grocery notes..."></textarea>
                            <button type="submit" class="save-changes-btn">Add</button>
                        </form>
                    </div>

                    <div class="form-inline" id="share-form-{{ $recipe->id }}">
                        <form action="{{ route('recipes.share.store', $recipe) }}" method="POST">
                            @csrf
                            <label for="expires_at_{{ $recipe->id }}">Link expiration (optional)</label>
                            <input type="datetime-local" id="expires_at_{{ $recipe->id }}" name="expires_at">
                            <button type="submit" class="save-changes-btn">Generate Share Link</button>
                            <p style="font-size:0.85em; color:#475569; margin-top:6px;">Shared recipes are read-only for your friends. Expired links automatically stop working.</p>
                        </form>

                        @if($recipe->shares->isNotEmpty())
                            <div class="share-links">
                                <p>Active share links</p>
                                @foreach($recipe->shares as $share)
                                    @php
                                        $shareUrl = route('recipes.share.show', $share->token);
                                    @endphp
                                    <div class="share-item">
                                    <div>
                                        <a class="share-url" href="{{ $shareUrl }}" target="_blank" rel="noopener">{{ $shareUrl }}</a>
                                        <div style="font-size:0.85em; color:#475569;">
                                            Created {{ $share->created_at->diffForHumans() }}
                                            @if($share->expires_at)
                                                — expires {{ $share->expires_at->diffForHumans() }}
                                            @else
                                                — no expiration
                                            @endif
                                        </div>
                                        <div class="social-links">
                                            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Check out this recipe: '.$shareUrl) }}" target="_blank" rel="noopener">Share to Twitter</a>
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener">Share to Facebook</a>
                                            <a href="mailto:?subject={{ rawurlencode('Recipe from PrepToEat') }}&body={{ rawurlencode('Thought you might like this recipe: '.$shareUrl) }}">Share via Email</a>
                                        </div>
                                    </div>
                                    <form action="{{ route('recipes.share.destroy', $share) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn" style="padding:6px 12px;">Disable</button>
                                    </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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

        <div class="chat-panel">
            <h2>Cooking Coach</h2>
            <div id="recipeChatLog" class="chat-log" aria-live="polite"></div>
            <form id="recipeChatForm" class="chat-form">
                @csrf
                <textarea id="chatMessage" name="message" placeholder="Ask for substitutions, techniques, or recipe ideas..."></textarea>
                <input type="text" id="chatIngredients" name="ingredients" placeholder="Optional: list ingredients you have on hand">
                <button type="submit" class="chat-submit">Ask PrepToEat</button>
            </form>
        </div>
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

        document.querySelectorAll('.toggle-inline').forEach(function(button) {
            button.addEventListener('click', function() {
                var targetId = this.getAttribute('data-target');
                var form = document.getElementById(targetId);
                if (!form) return;
                var expanded = form.style.display === 'block';
                form.style.display = expanded ? 'none' : 'block';
            });
        });

        document.querySelectorAll('.category-select').forEach(function(select) {
            select.addEventListener('change', function() {
                var recipeId = this.getAttribute('data-recipe-id');
                var customGroup = document.getElementById('custom_category_group_' + recipeId);
                if (!customGroup) return;
                if (this.value === 'other') {
                    customGroup.style.display = 'block';
                } else {
                    customGroup.style.display = 'none';
                }
            });
        });

        const chatForm = document.getElementById('recipeChatForm');
        const chatLog = document.getElementById('recipeChatLog');

        if (chatForm && chatLog) {
            chatForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const formData = new FormData(chatForm);
                const message = formData.get('message').toString().trim();
                const ingredients = formData.get('ingredients').toString().trim();
                if (!message) {
                    return;
                }

                appendChat('You', message);
                chatForm.reset();
                try {
                    const response = await fetch("{{ route('chat.respond') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': chatForm.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({ message, ingredients })
                    });

                    if (!response.ok) {
                        throw new Error('Bad response');
                    }

                    const data = await response.json();
                    appendChat('PrepToEat', data.reply || 'I\'m not sure how to help with that.');
                } catch (error) {
                    appendChat('PrepToEat', 'Something went wrong reaching the assistant. Try again shortly.');
                }
            });
        }

        function appendChat(author, text) {
            const wrapper = document.createElement('div');
            wrapper.className = 'chat-entry';
            const title = document.createElement('strong');
            title.textContent = author;
            const body = document.createElement('div');
            body.textContent = text;
            wrapper.appendChild(title);
            wrapper.appendChild(body);
            chatLog.appendChild(wrapper);
            chatLog.scrollTop = chatLog.scrollHeight;
        }
    </script>
</body>
</html>
