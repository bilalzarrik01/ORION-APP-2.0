<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Orion') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=archivo:300,400,500,600|playfair+display:500,700&display=swap" rel="stylesheet" />

        <style>
            :root {
                --bg: #07110c;
                --bg-2: #040806;
                --ink: #eaf7f1;
                --muted: #9fb7ab;
                --line: #1d2b24;
                --glow: #39f6b4;
                --panel: rgba(9, 18, 13, 0.9);
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: "Archivo", "Segoe UI", sans-serif;
                color: var(--ink);
                background:
                    radial-gradient(900px 500px at 15% 15%, rgba(57, 246, 180, 0.18), transparent 60%),
                    radial-gradient(700px 600px at 90% 25%, rgba(57, 246, 180, 0.12), transparent 60%),
                    linear-gradient(180deg, var(--bg), var(--bg-2));
                min-height: 100vh;
            }

            .frame {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 16px;
            }

            .panel {
                width: min(1100px, 94vw);
                background: var(--panel);
                border: 1px solid var(--line);
                border-radius: 26px;
                overflow: hidden;
                box-shadow: 0 40px 120px rgba(0, 0, 0, 0.7);
                position: relative;
            }

            .panel::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(500px 280px at 70% 20%, rgba(57, 246, 180, 0.18), transparent 70%),
                    radial-gradient(380px 240px at 80% 60%, rgba(57, 246, 180, 0.12), transparent 70%);
                pointer-events: none;
            }

            header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 24px 32px 10px;
                position: relative;
                z-index: 2;
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 12px;
                text-transform: uppercase;
                letter-spacing: 0.22em;
                font-size: 12px;
                color: var(--muted);
            }

            .brand img {
                width: 28px;
                height: 28px;
            }

            nav a {
                color: var(--muted);
                text-decoration: none;
                margin-left: 18px;
                font-size: 12px;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            nav a:hover { color: var(--ink); }

            .nav-btn {
                margin-left: 18px;
                padding: 8px 14px;
                border-radius: 999px;
                border: 1px solid rgba(234, 247, 241, 0.3);
                background: rgba(11, 20, 15, 0.8);
                color: var(--ink);
                font-weight: 600;
                text-transform: uppercase;
                font-size: 11px;
                letter-spacing: 0.16em;
            }

            .hero {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
                align-items: center;
                padding: 30px 32px 40px;
                position: relative;
                z-index: 2;
            }

            .hero h1 {
                font-family: "Playfair Display", "Times New Roman", serif;
                font-size: clamp(2.6rem, 3vw + 1.2rem, 4.2rem);
                margin: 0 0 12px;
                letter-spacing: 0.08em;
            }

            .hero p {
                color: var(--muted);
                max-width: 420px;
                line-height: 1.7;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 14px;
                margin-top: 22px;
            }

            .btn {
                padding: 12px 20px;
                border-radius: 999px;
                font-size: 12px;
                text-decoration: none;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                gap: 10px;
                border: 1px solid rgba(234, 247, 241, 0.35);
                color: var(--ink);
                background: rgba(10, 20, 14, 0.8);
            }

            .btn.primary {
                background: rgba(57, 246, 180, 0.16);
                border-color: rgba(57, 246, 180, 0.4);
            }

            .hero-media {
                position: relative;
                min-height: 320px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .hero-media::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(220px 220px at 60% 40%, rgba(57, 246, 180, 0.35), transparent 70%),
                    radial-gradient(260px 180px at 30% 70%, rgba(57, 246, 180, 0.2), transparent 70%);
                filter: blur(0px);
            }

            .hero-image {
                width: min(360px, 70%);
                height: auto;
                filter: drop-shadow(0 30px 60px rgba(0, 0, 0, 0.75));
                position: relative;
                z-index: 2;
            }

            .footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 32px 26px;
                color: var(--muted);
                font-size: 12px;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                position: relative;
                z-index: 2;
            }

            @media (max-width: 720px) {
                header { flex-direction: column; gap: 12px; }
                nav a, .nav-btn { margin-left: 0; }
                nav { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
                .footer { flex-direction: column; gap: 10px; text-align: center; }
            }
        </style>
    </head>
    <body>
        <div class="frame">
            <div class="panel">
                <header>
                    <div class="brand">
                        <img src="{{ asset('images/logo.png') }}" alt="Odin logo">
                        Orion
                    </div>
                    <nav>
                        <a href="#home">Home</a>
                        <a href="#about">About</a>
                        <a href="#services">Services</a>
                        <a href="#contact">Contact</a>
                        @if (Route::has('login'))
                            @auth
                                <a class="nav-btn" href="{{ url('/dashboard') }}">Dashboard</a>
                            @else
                                <a class="nav-btn" href="{{ route('login') }}">Login</a>
                                @if (Route::has('register'))
                                    <a class="nav-btn" href="{{ route('register') }}">Sign up</a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </header>

                <section id="home" class="hero">
                    <div>
                        <h1>ORION</h1>
                        <p>Where innovation knows no bounds. Capture, organize, and deploy your most valuable knowledge.</p>
                        <div class="actions">
                            <a class="btn primary" href="{{ route('login') }}">Discover</a>
                            <a class="btn" href="#about">Connect</a>
                        </div>
                    </div>

                    <div class="hero-media">
                        <img class="hero-image" src="{{ asset('images/logo.png') }}" alt="Odin logo">
                    </div>
                </section>

                <div class="footer">
                    <span>Odin Vault</span>
                    <span>Start a Project</span>
                    <span>Explore 2026</span>
                </div>
            </div>
        </div>
    </body>
</html>
