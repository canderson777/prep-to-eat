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
        .summary { background: #e3f5ff; border-radius: 6px; padding: 13px 14px; margin-top: 8px; color: #2c3e50; font-size: 1em;}
        .delete-btn { background: #ff5e5e; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; margin-top: 13px; font-size: 0.98em; transition: background .18s; }
        .delete-btn:hover { background: #e20000; }
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
            @foreach($recipes as $recipe)
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
                        <div class="section-header"><span class="icon">&#128161;</span> Summary:</div>
                        <div class="summary">{{ $recipe->summary }}</div>
                    @endif

                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">Delete Recipe</button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
</body>
</html> 