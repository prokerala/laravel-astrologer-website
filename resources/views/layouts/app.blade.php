<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings['site_name'] . ' - Vedic Astrology Services')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #6f42c1;
            --secondary-color: #fd7e14;
            --dark-color: #2c3e50;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(253, 126, 20, 0.1));
            padding: 80px 0;
        }

        .service-card {
            border: none;
            border-radius: 15px;
            padding: 30px;
            height: 100%;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 50px 0 30px;
            margin-top: 80px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
        }

        .zodiac-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .zodiac-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .zodiac-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            text-decoration: none;
            color: inherit;
        }

        .zodiac-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .astrologer-section {
            background: var(--light-bg);
            padding: 60px 0;
            margin: 40px 0;
        }

        .astrologer-photo {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .specialty-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 5px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-star me-2"></i>{{ $settings['site_name'] }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('astrologer.profile') }}">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="calculatorsDropdown" role="button" data-bs-toggle="dropdown">
                            Calculators
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('calculator.birth-chart') }}">Birth Chart</a></li>
                            <li><a class="dropdown-item" href="{{ route('calculator.kundli') }}">Kundli</a></li>
                            <li><a class="dropdown-item" href="{{ route('calculator.panchang') }}">Panchang</a></li>
                            <li><a class="dropdown-item" href="{{ route('calculator.compatibility') }}">Compatibility</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('horoscope.daily') }}">Horoscope</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog.index') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">About {{ $settings['site_name'] }}</h5>
                    <p>{{ $settings['site_description'] }}</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('calculator.birth-chart') }}" class="text-white-50">Birth Chart Calculator</a></li>
                        <li><a href="{{ route('calculator.kundli') }}" class="text-white-50">Free Kundli</a></li>
                        <li><a href="{{ route('horoscope.daily') }}" class="text-white-50">Daily Horoscope</a></li>
                        <li><a href="{{ route('astrologer.profile') }}" class="text-white-50">About Astrologer</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Connect With Us</h5>
                    <div class="d-flex gap-3 mb-3">
                        @if($settings['facebook_url'])<a href="{{ $settings['facebook_url'] }}" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>@endif
                        @if($settings['twitter_url'])<a href="{{ $settings['twitter_url'] }}" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>@endif
                        @if($settings['instagram_url'])<a href="{{ $settings['instagram_url'] }}" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>@endif
                        @if($settings['youtube_url'])<a href="{{ $settings['youtube_url'] }}" class="text-white"><i class="fab fa-youtube fa-2x"></i></a>@endif
                    </div>
                    <p class="text-white-50">Email: {{ $settings['contact_email'] }}<br>Phone: {{ $settings['contact_phone'] }}</p>
                </div>
            </div>
            <hr class="border-white-50 my-4">
            <div class="text-center text-white-50">
                <p>&copy; 2025 {{ $settings['site_name'] }}. All rights reserved. | Powered by Prokerala API</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    @if($settings['whatsapp_number'])
    <style>
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            background-color: #22c15e;
            text-decoration: none;
            color: #FFF;
        }

        .whatsapp-icon {
            margin-top: 16px;
        }
    </style>

    <a href="https://wa.me/{{ $settings['whatsapp_number'] }}"
       class="whatsapp-float"
       target="_blank"
       title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp whatsapp-icon"></i>
    </a>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
