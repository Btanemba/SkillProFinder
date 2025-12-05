<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Search Results - {{ config('app.name', 'SkillConnect') }}</title>

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
            }
            
            .container {
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
                margin-bottom: 40px;
            }
            
            .logo {
                font-size: 28px;
                font-weight: 700;
                color: white;
                text-decoration: none;
            }
            
            .back-link {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                transition: all 0.3s;
            }
            
            .back-link:hover {
                background: rgba(255, 255, 255, 0.3);
            }
            
            /* Search Summary */
            .search-summary {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 16px;
                padding: 30px;
                margin-bottom: 30px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }
            
            .search-summary h2 {
                color: #333;
                font-size: 24px;
                margin-bottom: 15px;
            }
            
            .search-params {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
                color: #666;
                font-size: 16px;
            }
            
            .search-param {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .search-param strong {
                color: #667eea;
            }
            
            /* Results */
            .results-header {
                color: white;
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 25px;
            }
            
            .no-results {
                background: white;
                border-radius: 16px;
                padding: 60px 40px;
                text-align: center;
                color: #666;
                font-size: 18px;
            }
            
            .results-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 25px;
            }
            
            .result-card {
                background: white;
                border-radius: 16px;
                padding: 25px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                transition: all 0.3s;
            }
            
            .result-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            }
            
            .result-header {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 20px;
                padding-bottom: 20px;
                border-bottom: 2px solid #f0f0f0;
            }
            
            .result-image {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid #667eea;
            }
            
            .result-info h3 {
                color: #333;
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 5px;
            }
            
            .result-skill {
                color: #667eea;
                font-weight: 500;
                font-size: 16px;
            }
            
            .result-details {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            
            .result-detail {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .result-label {
                color: #666;
                font-size: 14px;
            }
            
            .result-value {
                color: #333;
                font-weight: 600;
                font-size: 14px;
            }
            
            .skill-badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
            }
            
            .badge-beginner {
                background: #e3f2fd;
                color: #1976d2;
            }
            
            .badge-intermediate {
                background: #fff3e0;
                color: #f57c00;
            }
            
            .badge-advanced {
                background: #e8f5e9;
                color: #388e3c;
            }
            
            .badge-expert {
                background: #f3e5f5;
                color: #7b1fa2;
            }
            
            .result-price {
                font-size: 24px;
                font-weight: 700;
                color: #667eea;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 2px solid #f0f0f0;
            }
            
            .result-description {
                color: #666;
                font-size: 14px;
                line-height: 1.6;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #f0f0f0;
            }
            
            .btn-book {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                margin-top: 15px;
                text-decoration: none;
                display: block;
                text-align: center;
            }
            
            .btn-book:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            }
            
            /* Sample Pictures Gallery */
            .sample-pictures {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #f0f0f0;
            }
            
            .sample-pictures-label {
                color: #666;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 10px;
            }
            
            .sample-gallery {
                position: relative;
                width: 100%;
                height: 250px;
                border-radius: 12px;
                overflow: hidden;
                background: #f5f5f5;
            }
            
            .sample-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: none;
            }
            
            .sample-image.active {
                display: block;
            }
            
            .carousel-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(0, 0, 0, 0.5);
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: all 0.3s;
                z-index: 10;
            }
            
            .carousel-btn:hover {
                background: rgba(0, 0, 0, 0.7);
                transform: translateY(-50%) scale(1.1);
            }
            
            .carousel-btn.prev {
                left: 10px;
            }
            
            .carousel-btn.next {
                right: 10px;
            }
            
            .carousel-indicators {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 8px;
                z-index: 10;
            }
            
            .carousel-indicator {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                cursor: pointer;
                transition: all 0.3s;
            }
            
            .carousel-indicator.active {
                background: white;
                width: 24px;
                border-radius: 4px;
            }
            
            @media (max-width: 768px) {
                .results-grid {
                    grid-template-columns: 1fr;
                }
                
                .search-params {
                    flex-direction: column;
                    gap: 10px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <header>
                <a href="/" class="logo">SkillConnect</a>
                <a href="/" class="back-link">← Back to Search</a>
            </header>

            <!-- Search Summary -->
            <div class="search-summary">
                <h2>Search Results</h2>
                <div class="search-params">
                    @if(request('skill'))
                        <div class="search-param">
                            <strong>Skill:</strong> {{ request('skill') }}
                        </div>
                    @endif
                    @if(request('skill_type'))
                        <div class="search-param">
                            <strong>Type:</strong> {{ request('skill_type') }}
                        </div>
                    @endif
                    @if(request('country'))
                        <div class="search-param">
                            <strong>Country:</strong> {{ request('country') }}
                        </div>
                    @endif
                    @if(request('city'))
                        <div class="search-param">
                            <strong>City:</strong> {{ request('city') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Results -->
            @if($services->isEmpty())
                <div class="no-results">
                    <h3 style="color: #333; font-size: 24px; margin-bottom: 15px;">No Results Found</h3>
                    <p>We couldn't find any service providers matching your criteria. Try adjusting your search filters.</p>
                </div>
            @else
                <h2 class="results-header">Found {{ $services->count() }} {{ Str::plural('Provider', $services->count()) }}</h2>
                
                <div class="results-grid">
                    @foreach($services as $service)
                        <div class="result-card">
                            <div class="result-header">
                                @if($service->person->profile_picture)
                                    <img src="{{ asset('storage/' . $service->person->profile_picture) }}" alt="{{ $service->person->user->first_name ?? 'User' }}" class="result-image">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($service->person->user->first_name ?? 'User') }}&size=70&background=667eea&color=fff" alt="{{ $service->person->user->first_name ?? 'User' }}" class="result-image">
                                @endif
                                
                                <div class="result-info">
                                    <h3>{{ $service->person->user->first_name ?? '' }} {{ $service->person->user->last_name ?? '' }}</h3>
                                    <div class="result-skill">{{ $service->name }}@if($service->skill_type) - {{ $service->skill_type }}@endif</div>
                                </div>
                            </div>
                            
                            <div class="result-details">
                                <div class="result-detail">
                                    <span class="result-label">Experience</span>
                                    <span class="result-value">{{ $service->years_of_experience }} {{ Str::plural('year', $service->years_of_experience) }}</span>
                                </div>
                                
                                <div class="result-detail">
                                    <span class="result-label">Skill Level</span>
                                    <span class="skill-badge badge-{{ strtolower($service->skill_level) }}">
                                        {{ ucfirst($service->skill_level) }}
                                    </span>
                                </div>
                                
                                <div class="result-detail">
                                    <span class="result-label">Location</span>
                                    <span class="result-value">{{ $service->person->city ?? 'N/A' }}, {{ $service->person->country ?? '' }}</span>
                                </div>
                                
                                @if($service->certificate)
                                    <div class="result-detail">
                                        <span class="result-label">Certified</span>
                                        <span class="result-value">✓ Yes</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="result-price">£{{ number_format($service->price + 5, 2) }}</div>
                            
                            @if($service->description)
                                <p class="result-description">{{ Str::limit($service->description, 150) }}</p>
                            @endif
                            
                            @auth
                                <a href="{{ route('client.booking.show', $service->id) }}" class="btn-book">Book Now</a>
                            @else
                                <a href="{{ route('register', ['type' => 'client', 'service_id' => $service->id]) }}" class="btn-book">Register as Client to Book</a>
                            @endauth
                            
                            @if($service->sample_pictures && count($service->sample_pictures) > 0)
                                <div class="sample-pictures">
                                    <div class="sample-pictures-label">Sample Work ({{ count($service->sample_pictures) }} {{ Str::plural('image', count($service->sample_pictures)) }}):</div>
                                    <div class="sample-gallery" data-carousel="{{ $service->id }}">
                                        @foreach($service->sample_pictures as $index => $picture)
                                            <img src="{{ asset('storage/' . $picture) }}" alt="Sample work {{ $index + 1 }}" class="sample-image {{ $index === 0 ? 'active' : '' }}">
                                        @endforeach
                                        
                                        @if(count($service->sample_pictures) > 1)
                                            <button class="carousel-btn prev" onclick="changeSlide({{ $service->id }}, -1)">&#10094;</button>
                                            <button class="carousel-btn next" onclick="changeSlide({{ $service->id }}, 1)">&#10095;</button>
                                            
                                            <div class="carousel-indicators">
                                                @foreach($service->sample_pictures as $index => $picture)
                                                    <span class="carousel-indicator {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $service->id }}, {{ $index }})"></span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <script>
            // Carousel functionality
            const carousels = {};
            
            // Initialize carousels
            document.querySelectorAll('[data-carousel]').forEach(carousel => {
                const id = carousel.dataset.carousel;
                carousels[id] = {
                    currentIndex: 0,
                    images: carousel.querySelectorAll('.sample-image'),
                    indicators: carousel.querySelectorAll('.carousel-indicator')
                };
            });
            
            function changeSlide(serviceId, direction) {
                const carousel = carousels[serviceId];
                if (!carousel) return;
                
                carousel.currentIndex += direction;
                
                // Wrap around
                if (carousel.currentIndex < 0) {
                    carousel.currentIndex = carousel.images.length - 1;
                } else if (carousel.currentIndex >= carousel.images.length) {
                    carousel.currentIndex = 0;
                }
                
                updateCarousel(serviceId);
            }
            
            function goToSlide(serviceId, index) {
                const carousel = carousels[serviceId];
                if (!carousel) return;
                
                carousel.currentIndex = index;
                updateCarousel(serviceId);
            }
            
            function updateCarousel(serviceId) {
                const carousel = carousels[serviceId];
                if (!carousel) return;
                
                // Update images
                carousel.images.forEach((img, index) => {
                    img.classList.toggle('active', index === carousel.currentIndex);
                });
                
                // Update indicators
                carousel.indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === carousel.currentIndex);
                });
            }
        </script>
    </body>
</html>
