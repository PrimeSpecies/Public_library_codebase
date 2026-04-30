<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResearchHub | Discover & Share</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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

            <!-- <form action="index.php?action=search" method="POST" class="search-container">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" name="query" placeholder="Search by title, author, keywords..." required>
                <button type="submit" class="btn btn-black">Search</button>
            </form> -->

            <div class="stats-grid">
                <div class="stat-item"><h3>2.5M+</h3><p>Research Papers</p></div>
                <div class="stat-item"><h3>50K+</h3><p>Active Researchers</p></div>
                <div class="stat-item"><h3>180+</h3><p>Countries</p></div>
                <div class="stat-item"><h3>1M+</h3><p>Monthly Downloads</p></div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
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
                <a href="#top" class="btn btn-outline">Start Searching <i data-lucide="search"></i></a>
            </div>
        </div>
    </section>

    <script>
        // Initialize the icons
        lucide.createIcons();
    </script>
</body>
</html>