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
                <a href="{{ route('register') }}" style="color:#007bff;">Register</a>
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
        </form>

        <!-- Debug Information -->
        @if(config('app.debug'))
            <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border: 1px solid #ddd;">
                <h4>Debug Info:</h4>
                <p>Form Action: {{ url('/recipe') }}</p>
                <p>Method: POST</p>
                <p>CSRF Token: {{ csrf_token() }}</p>
            </div>
        @endif

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

                    @if(config('app.debug'))
                        <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border: 1px solid #ddd;">
                            <h4>Save Form Debug Info:</h4>
                            <p>Form Action: {{ route('recipes.save') }}</p>
                            <p>Method: POST</p>
                            <p>CSRF Token: {{ csrf_token() }}</p>
                            <p>Title: {{ $title ?? 'Not set' }}</p>
                            <p>Ingredients Length: {{ isset($ingredients) ? strlen($ingredients) : 'Not set' }} chars</p>
                            <p>Instructions Length: {{ isset($instructions) ? strlen($instructions) : 'Not set' }} chars</p>
                            <p>Summary Length: {{ isset($summary) ? strlen($summary) : 'Not set' }} chars</p>
                        </div>
                    @endif
                @else
                    <div style="margin-top:12px;">
                        <a href="{{ route('login') }}" style="color:#007bff;">Login</a> or
                        <a href="{{ route('register') }}" style="color:#007bff;">Register</a> to save recipes.
                    </div>
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
