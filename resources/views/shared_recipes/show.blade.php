<!DOCTYPE html>
<html>
<head>
    <title>{{ $recipe->title }} | Shared Recipe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; margin: 0; }
        .container { max-width: 760px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 18px #e3f5ff; padding: 28px 26px 36px 26px; }
        h1 { color: #1f2937; font-size: 2em; margin-bottom: 6px; }
        .meta { color: #64748b; margin-bottom: 18px; }
        .section { margin-bottom: 18px; }
        .section h2 { color: #2563eb; font-size: 1.2em; margin-bottom: 10px; }
        ul, ol { margin-left: 22px; }
        .notes { background: #f0f9ff; border-left: 4px solid #38b6ff; border-radius: 8px; padding: 12px 14px; color: #1f2937; }
        .banner { background: #dbeafe; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #1e3a8a; }
        .footer { margin-top: 24px; font-size: 0.9em; color: #64748b; text-align: center; }
        a.button { display: inline-block; margin-top: 20px; background: #38b6ff; color: #fff; padding: 10px 16px; border-radius: 6px; text-decoration: none; }
        a.button:hover { background: #109cff; }
        @media (max-width: 640px) {
            .container { margin: 14px; padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="banner">
            This recipe was shared with you from PrepToEat.
            @if($share->expires_at)
                Link expires {{ $share->expires_at->diffForHumans() }}.
            @else
                Link does not expire.
            @endif
        </div>
        <h1>{{ $recipe->title }}</h1>
        <div class="meta">Curated by {{ $recipe->user->name }}</div>

        <div class="section">
            <h2>Ingredients</h2>
            <ul>
                @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                    @if(trim($ingredient) !== '')
                        <li>{{ $ingredient }}</li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="section">
            <h2>Instructions</h2>
            <ol>
                @foreach(explode("\n", $recipe->instructions) as $step)
                    @if(trim($step) !== '')
                        <li>{{ $step }}</li>
                    @endif
                @endforeach
            </ol>
        </div>

        @if($recipe->summary)
            <div class="section">
                <h2>Notes & Tips</h2>
                <div class="notes">{{ $recipe->summary }}</div>
            </div>
        @endif

        <a class="button" href="{{ url('/') }}">Explore PrepToEat</a>

        <div class="footer">
            Want to save this recipe? Create a free PrepToEat account to build your own meal plan.
        </div>
    </div>
</body>
</html>
