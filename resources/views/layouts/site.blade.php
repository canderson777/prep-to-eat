<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'PrepToEat'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --brand: #0f172a;
            --surface: #ffffff;
            --surface-alt: #f1f5f9;
            --border: rgba(148, 163, 184, 0.35);
            --accent: #38b6ff;
            --accent-dark: #0f9ae0;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --success: #22c55e;
            --success-dark: #16a34a;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --radius-large: 20px;
            --shadow-lg: 0 22px 45px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, rgba(56, 182, 255, 0.18), transparent 55%),
                        radial-gradient(circle at top right, rgba(34, 197, 94, 0.12), transparent 50%),
                        #f8fafc;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            color: inherit;
        }

        .site-shell {
            width: min(1100px, 92vw);
            margin: 0 auto;
        }

        .site-header {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(15, 23, 42, 0.88));
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(10px);
        }

        .site-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.85rem 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 1.35rem;
            font-weight: 700;
            color: #f8fafc;
            text-decoration: none;
            letter-spacing: 0.01em;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: rgba(56, 182, 255, 0.2);
            color: #38b6ff;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .nav-toggle {
            display: none;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: transparent;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 0.45rem 0.55rem;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav-toggle:hover {
            background: rgba(148, 163, 184, 0.15);
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu a,
        .nav-menu button,
        .nav-menu span {
            font-size: 0.95rem;
            font-weight: 500;
            color: #e2e8f0;
            text-decoration: none;
            padding: 0.5rem 0.85rem;
            border-radius: 999px;
            transition: background 0.25s ease, color 0.25s ease;
            border: none;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-menu a:hover,
        .nav-menu button:hover {
            background: rgba(148, 163, 184, 0.22);
            color: #ffffff;
        }

        .nav-menu .nav-cta {
            background: linear-gradient(135deg, var(--accent), #60a5fa);
            color: #0f172a;
            box-shadow: 0 10px 25px rgba(96, 165, 250, 0.35);
        }

        .nav-menu .nav-cta:hover {
            background: linear-gradient(135deg, var(--accent-dark), #3b82f6);
            color: #f8fafc;
        }

        .nav-divider {
            width: 1px;
            height: 22px;
            background: rgba(148, 163, 184, 0.35);
            margin: 0 0.5rem;
        }

        .site-main {
            flex: 1 0 auto;
            padding: 2.5rem 0 4rem;
        }

        .content-shell {
            width: min(1100px, 92vw);
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .page-title {
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: #0f172a;
        }

        .page-subtitle {
            margin: 0 auto;
            max-width: 620px;
            color: var(--text-muted);
            font-size: 1rem;
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius-large);
            box-shadow: var(--shadow-lg);
            padding: clamp(1.5rem, 3vw, 2rem);
            border: 1px solid rgba(148, 163, 184, 0.12);
        }

        .card + .card {
            margin-top: 1.5rem;
        }

        .two-column {
            display: grid;
            gap: 2rem;
        }

        @media (min-width: 980px) {
            .two-column {
                grid-template-columns: 0.95fr 1.05fr;
                align-items: start;
            }
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #0f172a;
        }

        .input-control,
        textarea,
        select {
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.45);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            transition: border 0.2s ease, box-shadow 0.2s ease;
            background: #ffffff;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .input-control:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.18);
        }

        .btn {
            border-radius: 12px;
            border: none;
            padding: 0.65rem 1.35rem;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #60a5fa);
            color: #0f172a;
            box-shadow: 0 15px 30px rgba(56, 182, 255, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--accent-dark), #3b82f6);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(15, 23, 42, 0.08);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: rgba(15, 23, 42, 0.12);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #f87171);
            color: #fff;
            box-shadow: 0 14px 28px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, var(--danger-dark), #ef4444);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #4ade80);
            color: #0f172a;
            box-shadow: 0 14px 28px rgba(34, 197, 94, 0.25);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, var(--success-dark), #22c55e);
        }

        .btn-light {
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border: 1px solid rgba(226, 232, 240, 0.35);
        }

        .btn-light:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .btn-small {
            padding: 0.45rem 1.05rem;
            font-size: 0.85rem;
        }

        .alert {
            border-radius: 16px;
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(22, 163, 74, 0.12);
            color: #166534;
            border: 1px solid rgba(22, 163, 74, 0.2);
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.12);
            color: #b91c1c;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .recipe-grid {
            display: grid;
            gap: 1.75rem;
        }

        @media (min-width: 700px) {
            .recipe-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        .recipe-card {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            height: 100%;
        }

        .recipe-card header {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .recipe-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .recipe-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .recipe-tag {
            background: rgba(56, 182, 255, 0.12);
            color: var(--accent-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.01em;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
            color: #0f172a;
        }

        .icon-circle {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(56, 182, 255, 0.18);
            color: var(--accent);
        }

        .icon-circle svg {
            width: 18px;
            height: 18px;
        }

        .recipe-section ul,
        .recipe-section ol {
            margin: 0 0 1rem 1.2rem;
            color: #1f2937;
        }

        .note-box {
            background: rgba(56, 182, 255, 0.08);
            border: 1px solid rgba(56, 182, 255, 0.2);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            color: #0f172a;
        }

        .card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .edit-panel {
            background: rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(148, 163, 184, 0.35);
            border-radius: 16px;
            padding: 1.1rem;
            margin-top: 1rem;
            display: none;
        }

        .field-error {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.35rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(56, 182, 255, 0.12);
            color: var(--accent-dark);
            font-weight: 600;
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }

        .summary-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 880px) {
            .summary-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            background: rgba(15, 23, 42, 0.06);
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .notice-banner {
            display: none;
            border-radius: 16px;
            padding: 0.95rem 1.1rem;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.22);
            color: #166534;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }

        .notice-banner button {
            margin-left: 0.65rem;
        }

        .loading-indicator {
            display: none;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .loading-indicator.is-visible {
            display: inline-flex;
        }

        .spinner {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 3px solid rgba(148, 163, 184, 0.35);
            border-top-color: var(--accent);
            animation: spin 0.9s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .empty-state {
            text-align: center;
            color: var(--text-muted);
            display: grid;
            gap: 0.85rem;
        }

        .empty-state svg {
            width: 52px;
            height: 52px;
            margin: 0 auto;
            color: rgba(56, 182, 255, 0.45);
        }

        .muted-link {
            color: var(--text-muted);
            text-decoration: none;
        }

        .muted-link:hover {
            color: var(--accent-dark);
        }

        footer {
            background: transparent;
            padding: 2rem 0 3rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .nav-toggle {
                display: inline-flex;
            }

            .nav-menu {
                position: absolute;
                top: calc(100% + 0.5rem);
                left: 50%;
                transform: translateX(-50%);
                width: min(92vw, 420px);
                background: rgba(15, 23, 42, 0.97);
                border: 1px solid rgba(148, 163, 184, 0.25);
                border-radius: 18px;
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35);
                padding: 0.85rem;
                display: none;
                flex-direction: column;
                align-items: stretch;
                gap: 0.25rem;
            }

            .nav-menu a,
            .nav-menu button,
            .nav-menu span {
                width: 100%;
                justify-content: flex-start;
                border-radius: 12px;
            }

            .nav-menu.is-open {
                display: flex;
            }

            .site-header-inner {
                position: relative;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<header class="site-header">
    <div class="site-shell site-header-inner">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-badge">🍳</span>
            <span>PrepToEat</span>
        </a>
        <button class="nav-toggle" type="button" aria-label="Toggle navigation" data-toggle-nav>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" width="22" height="22">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <nav class="nav-menu" data-nav-menu>
            <a href="{{ url('/') }}">Home</a>
            @auth
                <a href="{{ route('recipes.index') }}">My Recipes</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>Hi, {{ \Illuminate\Support\Str::limit(Auth::user()->name, 18) }}</span>
                <div class="nav-divider" aria-hidden="true"></div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-light btn-small">Logout</button>
                </form>
            @else
                <a href="{{ route('recipes.local') }}">My Local Recipes</a>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="nav-cta">Create Account</a>
            @endauth
        </nav>
    </div>
</header>
<main class="site-main">
    @yield('content')
</main>
<footer>
    &copy; {{ now()->year }} PrepToEat. Crafted for easier home cooking.
</footer>
<script>
    (function() {
        const toggle = document.querySelector('[data-toggle-nav]');
        const menu = document.querySelector('[data-nav-menu]');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', function() {
            menu.classList.toggle('is-open');
        });
        document.addEventListener('click', function(evt) {
            if (!menu.classList.contains('is-open')) return;
            const target = evt.target;
            if (!menu.contains(target) && target !== toggle) {
                menu.classList.remove('is-open');
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
