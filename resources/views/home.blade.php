<!DOCTYPE html>
<html>
<head>
    <title>PrepToEat - AI Recipe Assistant</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; }
        .container { max-width: 540px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ddd; padding: 24px;}
        h1 { text-align: center; color: #333; margin-bottom: 32px; }
        textarea { width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc; resize: vertical;}
        button { background: #38b6ff; color: #fff; border: none; padding: 12px 24px; border-radius: 4px; font-size: 16px; cursor: pointer;}
        button:hover { background: #109cff; }
        .output {
            margin-top: 30px;
            padding: 28px 22px;
            background: #e3f5ff;
            border-radius: 6px;
        }
        /* Auth Links */
        .auth-links {
            text-align:right;
            margin-bottom:12px;
        }
        .auth-links a, .auth-links span {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            margin-left: 8px;
            margin-right: 2px;
        }
        /* Spinner */
        #spinner { display: none; text-align: center; margin-top: 20px; }
        .spin {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #38b6ff;
            border-radius: 50%;
            width: 36px; height: 36px;
            animation: spin 1s linear infinite;
            margin: 0 auto 8px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        /* Recipe Section Headers */
        h2 {
            font-size: 1.5em;
            color: #007bff;
            border-bottom: 2px solid #cce7ff;
            padding-bottom: 4px;
            margin-bottom: 18px;
            margin-top: 26px;
            letter-spacing: 0.02em;
        }
        .ai-recipe-output strong {
            display: block;
            color: #007bff;
            font-size: 1.18em;
            margin-top: 22px;
            margin-bottom: 10px;
            letter-spacing: 0.01em;
        }
        .ai-recipe-output .section-icon {
            margin-right: 7px;
            font-size: 1.1em;
            vertical-align: middle;
        }
        .recipe-title {
            font-size: 1.35em;
            color: #222;
            margin-bottom: 10px;
            margin-top: 0;
            font-weight: bold;
            letter-spacing: 0.01em;
        }
        .ai-recipe-output ul {
            margin-left: 22px;
            margin-bottom: 14px;
            list-style: disc inside;
            font-size: 1.07em;
        }
        .ai-recipe-output ol {
            margin-left: 22px;
            margin-bottom: 14px;
            list-style: decimal inside;
            font-size: 1.07em;
        }
        /* Responsive */
        @media (max-width: 650px) {
            body { margin: 8px;}
            .container { padding: 12px; max-width: 98vw;}
            h1 { font-size: 1.3em;}
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Auth Links -->
        <div class="auth-links">
            @auth
                <span>Welcome, {{ Auth::user()->name }} |</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#007bff;cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a> |
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>

        <h1>PrepToEat</h1>

        <!-- Spinner Loader -->
        <div id="spinner">
            <div class="spin"></div>
            <span>Finding the perfect recipe...</span>
        </div>

        <!-- Recipe Form -->
        <form method="POST" action="/recipe">
            @csrf
            <label for="recipe_link">Paste your recipe link or text below:</label><br>
            <textarea name="recipe_link" id="recipe_link" rows="6" placeholder="Paste recipe URL or text here..." required></textarea><br><br>
            <button type="submit">Get Recipe</button>
        </form>

        <!-- Output Result -->
        @isset($recipe)
            <div class="output">
                <h2>Your Recipe:</h2>
                {{-- Render the formatted recipe output --}}
                <div class="ai-recipe-output">
                    {!! str_replace(
                        // Replace section headers with icons + bold
                        [
                            '<strong>Title:</strong>',
                            '<strong>Ingredients:</strong>',
                            '<strong>Instructions:</strong>',
                            '<strong>Summary:</strong>',
                        ],
                        [
                            '<strong><i class="fas fa-utensils section-icon"></i>Title:</strong>',
                            '<strong><i class="fas fa-carrot section-icon"></i>Ingredients:</strong>',
                            '<strong><i class="fas fa-list-ol section-icon"></i>Instructions:</strong>',
                            '<strong><i class="fas fa-lightbulb section-icon"></i>Summary:</strong>',
                        ],
                        $recipe
                    ) !!}
                </div>
            </div>
        @endisset
    </div>
    <!-- Show spinner on form submit -->
    <script>
        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            document.getElementById('spinner').style.display = 'block';
        });
    </script>
</body>
</html>
