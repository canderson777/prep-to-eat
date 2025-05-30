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
        .output {
            margin-top: 30px;
            padding: 28px 22px;
            background: #e3f5ff;
            border-radius: 6px;
        }
        .ai-recipe-output strong {
            color: #007bff;
            font-size: 1.07em;
            display: block;
            margin-top: 22px;
            margin-bottom: 8px;
        }
        .ai-recipe-output ul, .ai-recipe-output ol {
            margin-left: 22px;
            margin-bottom: 16px;
        }
        .recipe-title {
            font-size: 1.7em;
            color: #1c1c1c;
            margin-bottom: 10px;
            margin-top: 0;
            font-weight: bold;
            letter-spacing: 0.01em;
        }
        #spinner {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        .spin {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #38b6ff;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            animation: spin 1s linear infinite;
            margin: 0 auto 8px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        /* Optional: Mobile styling for header links */
        @media (max-width: 600px) {
            .auth-links {
                text-align: center !important;
                margin-bottom: 18px !important;
                font-size: 1.09em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- AUTH LINKS (LOGIN/REGISTER OR USERNAME/LOGOUT) -->
        <div class="auth-links" style="text-align:right; margin-bottom:12px;">
            @auth
                <span>Welcome, {{ Auth::user()->name }} |</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" aria-label="Logout" style="background:none;border:none;color:#007bff;cursor:pointer;">Logout</button>
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
                <div class="ai-recipe-output">{!! $recipe !!}</div>
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
