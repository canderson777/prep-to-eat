<!DOCTYPE html>
<html>
<head>
    <title>PrepToEat - AI Recipe Assistant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; }
        .container { max-width: 500px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ddd; padding: 24px;}
        h1 { text-align: center; color: #333; }
        textarea { width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc; resize: vertical;}
        button { background: #38b6ff; color: #fff; border: none; padding: 12px 24px; border-radius: 4px; font-size: 16px; cursor: pointer;}
        button:hover { background: #109cff; }
        .output { margin-top: 30px; padding: 28px 22px; background: #e3f5ff; border-radius: 6px;}
        .ai-recipe-output strong { color: #007bff; font-size: 1.07em; display: block; margin-top: 22px; margin-bottom: 8px; }
        .ai-recipe-output ul, .ai-recipe-output ol { margin-left: 22px; margin-bottom: 16px; }
        .recipe-title { font-size: 1.7em; color: #1c1c1c; margin-bottom: 10px; margin-top: 0; font-weight: bold; letter-spacing: 0.01em; }
        #spinner { display: none; text-align: center; margin-top: 20px; }
        .spin { border: 6px solid #f3f3f3; border-top: 6px solid #38b6ff; border-radius: 50%; width: 36px; height: 36px; animation: spin 1s linear infinite; margin: 0 auto 8px auto; }
        @keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }
        .save-btn { background:#28b76b;color:#fff;padding:10px 18px;border-radius:4px;border:none;font-size:16px;cursor:pointer; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Auth Navigation -->
        <div style="text-align:right; margin-bottom:12px;">
            @auth
                <a href="{{ route('recipes.index') }}" style="color:#007bff;">My Recipes</a> |
                <span>Welcome, {{ Auth::user()->name }} |</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#007bff;cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color:#007bff;">Login</a> |
                <a href="{{ route('register') }}" style="color:#007bff;">Register</a> |
                <a href="{{ route('recipes.local') }}" style="color:#007bff;">My Local Recipes</a> |
                <span>Saved locally: <strong id="local-count">0</strong>/4</span>
            @endauth
        </div>

        <h1>PrepToEat</h1>

        <!-- Spinner Loader -->
        <div id="spinner">
            <div class="spin"></div>
            <span>Loading...</span>
        </div>

        <!-- Recipe Form -->
        <form method="POST" action="{{ url('/recipe') }}" id="recipeForm" enctype="multipart/form-data" novalidate>
            @csrf
            <label for="recipe_link">Paste your recipe link or text below:</label><br>
            <textarea name="recipe_link" id="recipe_link" rows="6" placeholder="Paste recipe URL or text here..." required></textarea><br><br>
            <input type="submit" value="Get Recipe" onclick="document.getElementById('spinner').style.display = 'block';" style="background: #38b6ff; color: #fff; border: none; padding: 12px 24px; border-radius: 4px; font-size: 16px; cursor: pointer;">
            <button type="button" onclick="window.location.href='/?clear=1'" style="margin-left:12px;">Refresh</button>
        </form>

        

        <!-- Output Result + Save Recipe -->
        @php
            $recipe = session('recipe');
            $title = session('title');
            $ingredients = session('ingredients');
            $instructions = session('instructions');
            $summary = session('summary');
        @endphp

        @if($recipe)
            <div class="output">
                <h2>Your Recipe:</h2>
                <div class="ai-recipe-output">{!! $recipe !!}</div>

                @auth
                    <form id="saveRecipeForm" action="{{ route('recipes.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="title" value="{{ $title ?? '' }}">
                        <input type="hidden" name="ingredients" value="{{ $ingredients ?? '' }}">
                        <input type="hidden" name="instructions" value="{{ $instructions ?? '' }}">
                        <input type="hidden" name="summary" value="{{ $summary ?? '' }}">
                        <div style="margin:14px 0;">
                            <label for="category"><strong>Category:</strong></label>
                            <select name="category" id="category" style="padding:6px; border-radius:4px; border:1px solid #ccc; margin-right:8px;">
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
                            <input type="text" name="custom_category" id="custom_category" placeholder="Or enter custom category" style="padding:6px; border-radius:4px; border:1px solid #ccc; display:none;">
                            @error('category')
                                <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                            @error('custom_category')
                                <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="save-btn">Save Recipe</button>
                    </form>
                    <script>
                        document.getElementById('category').addEventListener('change', function() {
                            const customCategoryInput = document.getElementById('custom_category');
                            if (this.value === 'other') {
                                customCategoryInput.style.display = 'inline-block';
                                customCategoryInput.required = true;
                            } else {
                                customCategoryInput.style.display = 'none';
                                customCategoryInput.required = false;
                            }
                        });
                    </script>

                    
                @else
                    <div style="margin-top:12px;">
                        <div style="margin:14px 0;">
                            <label for="guest_category"><strong>Category:</strong></label>
                            <select id="guest_category" style="padding:6px; border-radius:4px; border:1px solid #ccc; margin-right:8px;">
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
                            <input type="text" id="guest_custom_category" placeholder="Or enter custom category" style="padding:6px; border-radius:4px; border:1px solid #ccc; display:none;">
                        </div>
                        <button type="button" class="save-btn" id="guestSaveBtn">Save Locally</button>
                        <div id="guestSaveMsg" style="display:none; color:#065f46; margin-top:8px;">Saved to your browser.</div>
                        <div style="margin-top:12px;">
                            Create an account to sync your saved recipes later: 
                            <a href="{{ route('register') }}" style="color:#007bff;">Register</a> or
                            <a href="{{ route('login') }}" style="color:#007bff;">Login</a>
                        </div>
                    </div>
                    <script>
                        (function() {
                            const guestCategorySelect = document.getElementById('guest_category');
                            const guestCustomCategoryInput = document.getElementById('guest_custom_category');
                            if (guestCategorySelect) {
                                guestCategorySelect.addEventListener('change', function() {
                                    if (this.value === 'other') {
                                        guestCustomCategoryInput.style.display = 'inline-block';
                                        guestCustomCategoryInput.required = true;
                                    } else {
                                        guestCustomCategoryInput.style.display = 'none';
                                        guestCustomCategoryInput.required = false;
                                    }
                                });
                            }

                            const guestSaveBtn = document.getElementById('guestSaveBtn');
                            if (guestSaveBtn) {
                                guestSaveBtn.addEventListener('click', function() {
                                    try {
                                        const MAX_GUEST_RECIPES = 4;
                                        const baseRecipe = {
                                            title: @json($title ?? ''),
                                            ingredients: @json($ingredients ?? ''),
                                            instructions: @json($instructions ?? ''),
                                            summary: @json($summary ?? ''),
                                        };

                                        let chosenCategory = '';
                                        if (guestCategorySelect) {
                                            chosenCategory = guestCategorySelect.value === 'other' 
                                                ? (guestCustomCategoryInput.value || '').trim() 
                                                : guestCategorySelect.value;
                                        }

                                        const savedRecipes = JSON.parse(localStorage.getItem('guestRecipes') || '[]');
                                        if (savedRecipes.length >= MAX_GUEST_RECIPES) {
                                            const msg = document.getElementById('guestSaveMsg');
                                            if (msg) {
                                                msg.style.display = 'block';
                                                msg.style.color = '#b91c1c';
                                                msg.textContent = 'Limit reached (4). Register or log in to save more.';
                                            }
                                            const btn = document.getElementById('guestSaveBtn');
                                            if (btn) {
                                                btn.disabled = true;
                                                btn.style.opacity = '0.6';
                                                btn.style.cursor = 'not-allowed';
                                            }
                                            return;
                                        }
                                        const recipeToSave = Object.assign({}, baseRecipe, {
                                            category: chosenCategory || null,
                                            savedAt: new Date().toISOString(),
                                            id: (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : ('gr_' + Date.now())
                                        });
                                        savedRecipes.push(recipeToSave);
                                        localStorage.setItem('guestRecipes', JSON.stringify(savedRecipes));

                                        const msg = document.getElementById('guestSaveMsg');
                                        if (msg) {
                                            msg.style.display = 'block';
                                            msg.style.color = '#065f46';
                                            msg.textContent = 'Saved! (' + savedRecipes.length + ' of ' + MAX_GUEST_RECIPES + ')';
                                        }

                                        const countEl = document.getElementById('local-count');
                                        if (countEl) countEl.textContent = savedRecipes.length;

                                        // If we just hit the limit, disable the button and show limit message
                                        if (savedRecipes.length >= MAX_GUEST_RECIPES) {
                                            const btn = document.getElementById('guestSaveBtn');
                                            if (btn) {
                                                btn.disabled = true;
                                                btn.style.opacity = '0.6';
                                                btn.style.cursor = 'not-allowed';
                                            }
                                            if (msg) {
                                                msg.style.display = 'block';
                                                msg.style.color = '#b91c1c';
                                                msg.textContent = 'Limit reached (4). Register or log in to save more.';
                                            }
                                        }
                                    } catch (e) {
                                        alert('Could not save locally. Please ensure your browser allows local storage.');
                                    }
                                });
                            }
                        })();
                    </script>
                @endauth
                
                <!-- Q&A Section -->
                <div id="qa" style="margin-top: 24px; padding-top: 12px; border-top: 1px solid #bfe6ff;">
                    <h3 style="margin-bottom:10px;">Ask about this recipe</h3>
                    @if(session('error'))
                        <div style="background:#fdecea;color:#b71c1c;padding:10px;border-radius:4px;margin-bottom:10px;">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ url('/recipe/ask') }}">
                        @csrf
                        <label for="question" style="display:block;margin-bottom:6px;">Your question (e.g., "Substitute for buttermilk?" or "Make it gluten-free?")</label>
                        <textarea id="question" name="question" rows="3" placeholder="Type your question here..." required style="width:100%;padding:10px;border-radius:4px;border:1px solid #ccc;"></textarea>
                        @error('question')
                            <div style="color:#dc2626;margin-top:6px;">{{ $message }}</div>
                        @enderror
                        <div style="margin-top:10px;">
                            <button type="submit">Ask</button>
                        </div>
                    </form>

                    @php
                        $qaQuestion = session('qa_question');
                        $qaAnswer = session('qa_answer');
                    @endphp
                    @if(!empty($qaAnswer))
                        <div style="margin-top:16px;background:#fff;border:1px solid #cfe9ff;border-radius:6px;padding:12px;">
                            <div style="color:#555;font-size:0.95em;margin-bottom:6px;"><strong>You asked:</strong> {{ $qaQuestion }}</div>
                            <div style="white-space:pre-wrap;">{{ $qaAnswer }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <script>
        (function() {
            try {
                const MAX_GUEST_RECIPES = 4;
                const saved = JSON.parse(localStorage.getItem('guestRecipes') || '[]');
                const el = document.getElementById('local-count');
                if (el) el.textContent = saved.length;
                const guestSaveBtn = document.getElementById('guestSaveBtn');
                if (guestSaveBtn && saved.length >= MAX_GUEST_RECIPES) {
                    guestSaveBtn.disabled = true;
                    guestSaveBtn.style.opacity = '0.6';
                    guestSaveBtn.style.cursor = 'not-allowed';
                    guestSaveBtn.title = 'Limit reached (4). Register or log in to save more.';
                    const msg = document.getElementById('guestSaveMsg');
                    if (msg) {
                        msg.style.display = 'block';
                        msg.style.color = '#b91c1c';
                        msg.textContent = 'Limit reached (4). Register or log in to save more.';
                    }
                }
            } catch (e) { /* ignore */ }
        })();
    </script>
</body>
</html>
