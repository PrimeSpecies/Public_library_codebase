<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResearchHub | Discover & Share</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #007bff;
            --black: #000000;
            --gray-bg: #f8f9fa;
            --text-muted: #6c757d;
        }

        /* --- Base Reset --- */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--black);
            line-height: 1.5;
            overflow-x: hidden;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .text-center { text-align: center; }

        /* --- Navbar --- */
        .navbar {
            padding: 20px 0;
            background: transparent;
            position: absolute;
            width: 100%;
            z-index: 10;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; }

        .nav-auth .btn-link {
            text-decoration: none;
            color: var(--black);
            margin-right: 20px;
            font-weight: 600;
        }

        /* --- Hero Section --- */
        .hero {
            background: linear-gradient(135deg, rgba(0,123,255,0.1), transparent);
            padding: 160px 0 80px;
        }

        .badge {
            background: black;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            display: inline-block;
        }

        .hero-title { 
            font-size: clamp(2.5rem, 8vw, 4rem); 
            margin: 20px 0; 
            letter-spacing: -2px; 
            line-height: 1.1;
        }

        .hero-subtitle { font-size: clamp(1.2rem, 3vw, 1.8rem); margin-bottom: 10px; }

        .hero-text { color: var(--text-muted); max-width: 600px; margin: 0 auto 40px; }

        /* --- Buttons --- */
        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-black { background: black; color: white; }
        .btn-black:hover { opacity: 0.8; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-outline { border: 1px solid white; color: white; }

        /* --- Features --- */
        .features {
            background: #000;
            color: white;
            padding: 80px 0;
        }

        .section-title { margin-bottom: 50px; font-size: 2rem; font-weight: 800; }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .card {
            background: rgba(255,255,255,0.05);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            text-align: left;
        }

        .icon-box {
            background: var(--primary);
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        /* --- Stats --- */
        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            margin-top: 50px;
        }

        .stat-item h3 { font-size: 2.2rem; margin: 0; }
        .stat-item p { color: var(--text-muted); margin: 0; }

        /* --- CTA --- */
        .cta {
            background: linear-gradient(to right, #000, #1a1a1a);
            color: white;
            padding: 100px 0;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        /* --- RESPONSIVE MEDIA QUERIES --- */

       /* --- RESPONSIVE MEDIA QUERIES (UPDATED) --- */

@media (max-width: 768px) {
    /* 1. The Header: Keep horizontal, add a slight background for readability */
    .navbar { 
        position: absolute; 
        padding: 12px 0; 
        background: rgba(255, 255, 255, 0.95); /* Slight white tint */
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .nav-content { 
        flex-direction: row; /* Forces logo and buttons to stay on the same line */
        justify-content: space-between; 
        gap: 10px; 
    }

    .logo { 
        font-size: 1.25rem; /* Slightly smaller logo for mobile */
    }

    .nav-auth {
        display: flex;
        align-items: center;
        gap: 12px; /* Tighter spacing between Login and Create Account */
    }

    .nav-auth .btn-link { 
        margin-right: 0; 
        font-size: 0.9rem; 
    }

    /* Shrink the nav button so it doesn't break the layout */
    .nav-auth .btn { 
        padding: 8px 14px; 
        font-size: 0.85rem; 
        border-radius: 8px;
    }

    /* 2. Adjust Hero padding so the text doesn't hide behind the navbar */
    .hero { 
        padding: 120px 0 60px; 
    }

    /* 3. Hero & CTA Buttons: Better touch targets */
    .cta-buttons { 
        flex-direction: column; 
        align-items: stretch; /* Forces buttons to be exactly the same width */
        width: 100%; 
        padding: 0 10px;
    }

    .btn { 
        width: 100%; 
        max-width: 100%; /* Removes previous constraints */
        padding: 15px 20px; /* Taller padding for easier thumb tapping */
        font-size: 1rem;
        justify-content: center;
    }

    /* 4. Fix other layouts */
    .stats-grid { flex-direction: column; align-items: center; gap: 30px; }
    .card { padding: 25px; }
}

/* For extremely narrow phones (like iPhone SE) */
@media (max-width: 380px) {
    .logo { font-size: 1.1rem; }
    .nav-auth .btn { padding: 6px 10px; font-size: 0.8rem; }
    .nav-auth .btn-link { display: none; } /* Hides plain text 'Login' if it gets too cramped */
}

        @media (max-width: 480px) {
            .hero-title { font-size: 2.5rem; }
            .section-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">ResearchHub</div>
            <div class="nav-auth">
                <a href="index.php?action=login" class="btn-link">Login</a>
                <a href="index.php?action=register" class="btn btn-black">Create Account</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container text-center">
            <span class="badge">The Future of Academic Research</span>
            <h1 class="hero-title">ResearchHub</h1>
            <p class="hero-subtitle">Discover, Share, and Accelerate Research Worldwide</p>
            <p class="hero-text">Join thousands of researchers accessing millions of academic papers, theses, and documents.</p>

            <div class="stats-grid">
                <div class="stat-item"><h3>2.5M+</h3><p>Research Papers</p></div>
                <div class="stat-item"><h3>50K+</h3><p>Active Researchers</p></div>
                <div class="stat-item"><h3>180+</h3><p>Countries</p></div>
                <div class="stat-item"><h3>1M+</h3><p>Monthly Downloads</p></div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container text-center">
            <h2 class="section-title">Everything you need to advance research</h2>
            <div class="features-grid">
                <div class="card">
                    <div class="icon-box"><i data-lucide="zap"></i></div>
                    <h3>Lightning Fast Search</h3>
                    <p>Find exactly what you need in milliseconds with our context-aware search engine.</p>
                </div>
                <div class="card">
                    <div class="icon-box"><i data-lucide="shield"></i></div>
                    <h3>Secure & Reliable</h3>
                    <p>Your research is protected with enterprise-grade security and permanent archival.</p>
                </div>
                <div class="card">
                    <div class="icon-box"><i data-lucide="globe"></i></div>
                    <h3>Global Collaboration</h3>
                    <p>Connect with researchers worldwide. Share insights and build global networks.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container text-center">
            <h2>Ready to accelerate your research?</h2>
            <div class="cta-buttons">
                <a href="index.php?action=register" class="btn btn-primary">Upload Your Research <i data-lucide="arrow-right"></i></a>
                <a href="index.php?action=login" class="btn btn-outline">Start Browsing <i data-lucide="search"></i></a>
            </div>
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>