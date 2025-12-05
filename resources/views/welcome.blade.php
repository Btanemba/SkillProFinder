<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SkillConnect') }} - Find Skills, Offer Services</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                overflow-x: hidden;
                position: relative;
            }
            
            /* Animated background circles */
            .bg-circle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                animation: float 20s infinite ease-in-out;
            }
            
            .circle-1 { width: 300px; height: 300px; top: -100px; left: -100px; animation-delay: 0s; }
            .circle-2 { width: 200px; height: 200px; top: 50%; right: -50px; animation-delay: 3s; }
            .circle-3 { width: 150px; height: 150px; bottom: 100px; left: 10%; animation-delay: 6s; }
            
            @keyframes float {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                50% { transform: translateY(-30px) rotate(180deg); }
            }
            
            .container {
                position: relative;
                z-index: 10;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }
            
            /* Header */
            header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 0;
                color: white;
            }
            
            .logo {
                font-size: 28px;
                font-weight: 700;
                color: white;
                text-decoration: none;
            }
            
            .nav-links {
                display: flex;
                gap: 20px;
            }
            
            .nav-links a {
                color: white;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s;
                padding: 8px 20px;
                border-radius: 8px;
            }
            
            .nav-links a:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            /* Mobile Menu Button */
            .mobile-menu-btn {
                display: none;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                padding: 10px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 24px;
                transition: all 0.3s;
            }

            .mobile-menu-btn:hover {
                background: rgba(255, 255, 255, 0.3);
            }

            .mobile-menu {
                display: none;
                position: fixed;
                top: 0;
                right: -100%;
                width: 80%;
                max-width: 300px;
                height: 100vh;
                background: rgba(102, 126, 234, 0.98);
                backdrop-filter: blur(10px);
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                transition: right 0.3s ease;
                padding: 20px;
            }

            .mobile-menu.active {
                right: 0;
            }

            .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .mobile-menu-close {
                background: none;
                border: none;
                color: white;
                font-size: 30px;
                cursor: pointer;
                padding: 0;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-nav-links {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .mobile-nav-links a {
                color: white;
                text-decoration: none;
                padding: 15px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.1);
                text-align: center;
                font-weight: 500;
                transition: all 0.3s;
            }

            .mobile-nav-links a:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateX(5px);
            }

            .mobile-register-section {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
            }

            .mobile-register-title {
                color: white;
                text-align: center;
                margin-bottom: 15px;
                font-size: 14px;
                font-weight: 600;
                opacity: 0.9;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .mobile-register-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                color: white;
                text-decoration: none;
                padding: 14px 20px;
                border-radius: 12px;
                text-align: center;
                font-weight: 600;
                transition: all 0.3s;
                font-size: 15px;
                margin-bottom: 12px;
            }

            .mobile-register-btn svg {
                width: 18px;
                height: 18px;
            }

            .mobile-register-btn.provider {
                background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
                color: #667eea !important;
                box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
            }

            .mobile-register-btn.provider:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
            }

            .mobile-register-btn.client {
                background: rgba(255, 255, 255, 0.15);
                color: white !important;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }

            .mobile-register-btn.client:hover {
                background: rgba(255, 255, 255, 0.25);
                border-color: rgba(255, 255, 255, 0.5);
                transform: translateY(-2px);
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .mobile-overlay.active {
                display: block;
            }
            
            .btn-primary {
                background: white;
                color: #667eea !important;
                padding: 10px 25px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s;
                display: inline-block;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                background: rgba(255, 255, 255, 0.95);
            }
            
            /* Hero Section */
            .hero {
                text-align: center;
                color: white;
                padding: 80px 20px 40px;
            }
            
            .hero h1 {
                font-size: 56px;
                font-weight: 700;
                margin-bottom: 20px;
                line-height: 1.2;
            }
            
            .hero p {
                font-size: 20px;
                margin-bottom: 40px;
                opacity: 0.95;
            }
            
            /* Search Box */
            .search-box {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 40px;
                max-width: 900px;
                margin: 0 auto 40px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            
            .search-title {
                font-size: 28px;
                font-weight: 600;
                color: #333;
                margin-bottom: 30px;
            }
            
            .search-form {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .search-form select {
                padding: 12px 15px;
                border: 2px solid #e0e0e0;
                border-radius: 10px;
                font-size: 16px;
                background: white;
                color: #333;
                transition: all 0.3s;
                cursor: pointer;
            }
            
            .search-form select:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }
            
            .search-buttons {
                display: flex;
                gap: 15px;
                grid-column: 1 / -1;
                margin-top: 10px;
            }
            
            .btn-search, .btn-clear {
                padding: 12px 30px;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.3s;
                flex: 1;
            }
            
            .btn-search {
                background: #667eea;
                color: white;
            }
            
            .btn-search:hover {
                background: #5568d3;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            }
            
            /* Hero Section */
            
            .btn-white {
                background: white;
                color: #667eea;
            }
            
            .btn-outline {
                background: transparent;
                color: white;
                border: 2px solid white;
            }
            
            .btn-large:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            }
            
            /* Features Section */
            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                padding: 60px 20px;
            }
            
            .feature-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 16px;
                padding: 40px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s;
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            }
            
            .feature-icon {
                font-size: 48px;
                margin-bottom: 20px;
            }
            
            .feature-card h3 {
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 15px;
                color: #333;
            }
            
            .feature-card p {
                color: #666;
                line-height: 1.6;
            }
            
            /* Stats Section */
            .stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 30px;
                padding: 40px 20px;
                text-align: center;
            }
            
            .stat-item {
                color: white;
            }
            
            .stat-number {
                font-size: 48px;
                font-weight: 700;
                margin-bottom: 10px;
            }
            
            .stat-label {
                font-size: 18px;
                opacity: 0.9;
            }
            
            /* Footer */
            footer {
                text-align: center;
                padding: 40px 20px;
                color: white;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            @media (max-width: 768px) {
                .hero h1 { font-size: 36px; }
                .hero p { font-size: 16px; }
                .nav-links { display: none; }
                .mobile-menu-btn { display: block; }
                .mobile-menu { display: block; }
            }
        </style>
    </head>
    <body>
        <!-- Background Circles -->
        <div class="bg-circle circle-1"></div>
        <div class="bg-circle circle-2"></div>
        <div class="bg-circle circle-3"></div>

        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileMenu()"></div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <span class="logo">SkillConnect</span>
                <button class="mobile-menu-close" onclick="closeMobileMenu()">×</button>
            </div>
            <div class="mobile-nav-links">
                @auth
                    <a href="{{ url('/admin') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; color: white; text-decoration: none; padding: 15px; border-radius: 8px; background: rgba(255, 255, 255, 0.1); text-align: center; font-weight: 500; border: none; cursor: pointer; font-size: 16px;">
                            Logout
                        </button>
                    </form>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <div class="mobile-register-section">
                            <p class="mobile-register-title">Register as</p>
                            <a href="{{ route('register') }}?type=provider" class="mobile-register-btn provider">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Service Provider
                            </a>
                            <a href="{{ route('register') }}?type=client" class="mobile-register-btn client">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Client
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
        
        <div class="container">
            <!-- Header -->
            <header>
                <a href="/" class="logo">SkillConnect</a>
                <button class="mobile-menu-btn" onclick="openMobileMenu()">☰</button>
                <nav class="nav-links">
                    @auth
                        <a href="{{ url('/admin') }}" class="btn-primary">Dashboard</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}">Login</a>
                        @endif
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                        @endif
                    @endauth
                </nav>
            </header>

            <!-- Hero Section -->
            <section class="hero">
                <h1>Connect Skills with Opportunities</h1>
                <p>Find talented professionals or showcase your expertise to clients worldwide</p>
                
                <!-- Search Box -->
                <div class="search-box">
                    <h2 class="search-title">Find the Perfect Service Provider</h2>
                    <form action="{{ route('search.services') }}" method="GET" class="search-form">
                        <select name="skill" id="skill" required>
                            <option value="">Select Skill</option>
                            @foreach($skills as $skill)
                                <option value="{{ $skill }}">{{ $skill }}</option>
                            @endforeach
                        </select>
                        
                        <select name="country" id="country" required>
                            <option value="GB" selected>United Kingdom</option>
                        </select>
                        
                        <select name="city" id="city" required>
                            <option value="">Select City</option>
                        </select>
                        
                        <div class="search-buttons">
                            <button type="submit" class="btn-search">SEARCH</button>
                            <button type="button" class="btn-clear" onclick="document.querySelector('form').reset();">CLEAR</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Optional: Show button to toggle features when not searching -->
            <div style="text-align: center; margin: 40px 0;">
                <button onclick="document.getElementById('features').style.display = document.getElementById('features').style.display === 'none' ? 'grid' : 'none';" 
                        style="background: white; color: #667eea; padding: 12px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Show More About Us
                </button>
            </div>

            <!-- Features Section -->
            <section id="features" class="features" style="display: none;">
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>For Clients</h3>
                    <p>Browse through thousands of skilled professionals. View portfolios, read reviews, and hire the perfect match for your project needs.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💼</div>
                    <h3>For Providers</h3>
                    <p>Showcase your skills, set your rates, and connect with clients. Build your portfolio and grow your professional network.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Quick & Easy</h3>
                    <p>Simple registration process. Upload your portfolio, set your pricing, and start connecting within minutes.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure Platform</h3>
                    <p>Your data is protected with industry-standard security. Safe payments and verified profiles ensure peace of mind.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3>Quality Services</h3>
                    <p>All providers showcase their experience, skills, and sample work. Make informed decisions with detailed profiles.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🌍</div>
                    <h3>Global Reach</h3>
                    <p>Connect with talent from around the world. No geographical boundaries, unlimited possibilities.</p>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Skilled Providers</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Active Clients</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Service Categories</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support Available</div>
                </div>
            </section>

            <!-- Footer -->
            <footer>
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'SkillConnect') }}. All rights reserved.</p>
                <p style="margin-top: 10px; opacity: 0.8;">Connecting talent with opportunity</p>
            </footer>
        </div>
        
        <!-- JavaScript -->
        <script>
            // Load UK cities from backend API
            const citySelect = document.getElementById('city');
            
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            
            fetch('/api/cities/GB')
                .then(response => response.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    Object.entries(cities).forEach(([value, text]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = text;
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading cities:', error);
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    citySelect.disabled = false;
                });
        
        // Mobile Menu Functions
        function openMobileMenu() {
            document.getElementById('mobileMenu').classList.add('active');
            document.getElementById('mobileOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.remove('active');
            document.getElementById('mobileOverlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        </script>
    </body>
</html>
