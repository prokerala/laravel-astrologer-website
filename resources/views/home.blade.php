@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Discover Your Cosmic Journey</h1>
        <p class="lead mb-5">Unlock the mysteries of your life with authentic Vedic astrology</p>
        <a href="{{ route('calculator.birth-chart') }}" class="btn btn-primary btn-lg">
            Get Your Free Birth Chart
        </a>
    </div>
</section>

<!-- Astrologer Section -->
<section class="astrologer-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{ asset($astrologer['photo']) }}" alt="{{ $astrologer['name'] }}" class="astrologer-photo">
            </div>
            <div class="col-lg-8">
                <h2 class="mb-3">Meet Your Astrologer</h2>
                <h3 class="h4 text-primary mb-2">{{ $astrologer['name'] }}</h3>
                <p class="text-muted mb-3">{{ $astrologer['title'] }} | {{ $astrologer['experience'] }}</p>
                <p class="mb-4">With decades of experience in Vedic astrology, I help individuals navigate life's challenges and discover their true potential through the ancient wisdom of the stars. My approach combines traditional knowledge with practical guidance for modern life.</p>
                <div class="mb-4">
                    @foreach($astrologer['specialties'] as $specialty)
                    <span class="specialty-badge">{{ $specialty }}</span>
                    @endforeach
                </div>
                <a href="{{ route('astrologer.profile') }}" class="btn btn-primary">View Full Profile</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary ms-2">Book Consultation</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Services</h2>
        <div class="row g-4">
            @foreach($services as $service)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route($service['route']) }}" class="text-decoration-none">
                    <div class="service-card text-center">
                        <div class="service-icon">
                            <i class="{{ $service['icon'] }}"></i>
                        </div>
                        <h4 class="mb-3">{{ $service['title'] }}</h4>
                        <p class="text-muted">{{ $service['description'] }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Explore Your Destiny?</h2>
        <p class="lead mb-4">Get personalized astrological insights based on your birth details</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('horoscope.daily') }}" class="btn btn-primary">Daily Horoscope</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-primary">Book Consultation</a>
        </div>
    </div>
</section>
@endsection
