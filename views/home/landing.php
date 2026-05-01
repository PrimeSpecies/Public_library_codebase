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
        <div class="logo"><?= __('nav.brand') ?></div>
        <div class="nav-auth">
            <!-- Language switcher -->
            <div style="display:flex;align-items:center;gap:4px;margin-right:12px;">
                <a href="index.php?action=set-lang&lang=en"
                   style="font-size:0.75rem;font-weight:600;padding:3px 9px;border-radius:5px;text-decoration:none;
                          <?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'background:#0f172a;color:white;' : 'color:#64748b;' ?>">
                    EN
                </a>
                <a href="index.php?action=set-lang&lang=fr"
                   style="font-size:0.75rem;font-weight:600;padding:3px 9px;border-radius:5px;text-decoration:none;
                          <?= ($_SESSION['lang'] ?? 'en') === 'fr' ? 'background:#0f172a;color:white;' : 'color:#64748b;' ?>">
                    FR
                </a>
            </div>
            <a href="index.php?action=login" class="btn-link"><?= __('landing.nav_login') ?></a>
            <a href="index.php?action=register" class="btn btn-black"><?= __('landing.nav_register') ?></a>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container text-center">
        <span class="badge"><?= __('landing.hero_badge') ?></span>
        <h1 class="hero-title"><?= __('nav.brand') ?></h1>
        <p class="hero-subtitle"><?= __('landing.hero_subtitle') ?></p>
        <p class="hero-text"><?= __('landing.hero_text') ?></p>

        <div class="stats-grid">
            <div class="stat-item"><h3>2.5M+</h3><p><?= __('landing.stat_papers') ?></p></div>
            <div class="stat-item"><h3>50K+</h3><p><?= __('landing.stat_researchers') ?></p></div>
            <div class="stat-item"><h3>180+</h3><p><?= __('landing.stat_countries') ?></p></div>
            <div class="stat-item"><h3>1M+</h3><p><?= __('landing.stat_downloads') ?></p></div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container text-center">
        <h2 class="section-title"><?= __('landing.features_title') ?></h2>
        <div class="features-grid">
            <div class="card">
                <div class="icon-box"><i data-lucide="zap"></i></div>
                <h3><?= __('landing.feature_search_title') ?></h3>
                <p><?= __('landing.feature_search_text') ?></p>
            </div>
            <div class="card">
                <div class="icon-box"><i data-lucide="shield"></i></div>
                <h3><?= __('landing.feature_secure_title') ?></h3>
                <p><?= __('landing.feature_secure_text') ?></p>
            </div>
            <div class="card">
                <div class="icon-box"><i data-lucide="globe"></i></div>
                <h3><?= __('landing.feature_collab_title') ?></h3>
                <p><?= __('landing.feature_collab_text') ?></p>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container text-center">
        <h2><?= __('landing.cta_title') ?></h2>
        <div class="cta-buttons">
            <a href="index.php?action=register" class="btn btn-primary">
                <?= __('landing.cta_upload') ?> <i data-lucide="arrow-right"></i>
            </a>
            <a href="index.php?action=login" class="btn btn-outline">
                <?= __('landing.cta_browse') ?> <i data-lucide="search"></i>
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer style="background:#0f172a;color:#94a3b8;padding:48px 0 24px;">
    <div class="container">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;margin-bottom:40px;">

            <!-- Brand -->
            <div>
                <div style="font-family:'IBM Plex Mono',monospace;font-weight:600;font-size:1.1rem;color:white;margin-bottom:12px;">
                    <?= __('nav.brand') ?>
                </div>
                <p style="font-size:0.85rem;line-height:1.6;max-width:280px;">
                    <?= __('landing.footer_desc') ?>
                </p>
                <div style="display:flex;gap:12px;margin-top:16px;">
                    <a href="#" style="color:#64748b;transition:color 0.15s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#64748b'">
                        <i data-lucide="twitter" style="width:18px;"></i>
                    </a>
                    <a href="#" style="color:#64748b;transition:color 0.15s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#64748b'">
                        <i data-lucide="github" style="width:18px;"></i>
                    </a>
                    <a href="#" style="color:#64748b;transition:color 0.15s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#64748b'">
                        <i data-lucide="linkedin" style="width:18px;"></i>
                    </a>
                </div>
            </div>

            <!-- Platform -->
            <div>
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#475569;margin-bottom:14px;">
                    <?= __('landing.footer_col_platform') ?>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                    <li><a href="index.php?action=login" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.nav_login') ?></a></li>
                    <li><a href="index.php?action=register" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.nav_register') ?></a></li>
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_browse') ?></a></li>
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_upload') ?></a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#475569;margin-bottom:14px;">
                    <?= __('landing.footer_col_resources') ?>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_docs') ?></a></li>
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_faq') ?></a></li>
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_support') ?></a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#475569;margin-bottom:14px;">
                    <?= __('landing.footer_col_legal') ?>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_privacy') ?></a></li>
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_terms') ?></a></li>
                    <li><a href="#" style="color:#94a3b8;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'"><?= __('landing.footer_cookies') ?></a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom bar -->
        <div style="border-top:1px solid #1e293b;padding-top:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <p style="font-size:0.78rem;margin:0;">
                © <?= date('Y') ?> <?= __('nav.brand') ?>. <?= __('landing.footer_rights') ?>
            </p>
            <div style="display:flex;gap:16px;">
                <a href="index.php?action=set-lang&lang=en"
                   style="font-size:0.75rem;font-weight:600;text-decoration:none;
                          <?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'color:white;' : 'color:#475569;' ?>">EN</a>
                <a href="index.php?action=set-lang&lang=fr"
                   style="font-size:0.75rem;font-weight:600;text-decoration:none;
                          <?= ($_SESSION['lang'] ?? 'en') === 'fr' ? 'color:white;' : 'color:#475569;' ?>">FR</a>
            </div>
        </div>
    </div>
</footer>

<script>
    lucide.createIcons();
</script>
</body>
</html>