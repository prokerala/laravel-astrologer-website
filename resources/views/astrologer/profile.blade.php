@extends('layouts.app')

@section('title', 'About ' . $astrologer['name'] . ' - Divine Astrology')

@section('content')
<div class="container py-5">
    <div class="profile-card">
        <div class="row">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{ asset($astrologer['photo']) }}" alt="{{ $astrologer['name'] }}" class="astrologer-photo mb-4">
                <h2 class="h3 mb-2">{{ $astrologer['name'] }}</h2>
                <p class="text-muted mb-4">{{ $astrologer['title'] }}</p>

                <div class="d-flex justify-content-center gap-4 mb-4">
                    <div class="text-center">
                        <h3 class="h2 text-primary mb-0">{{ $astrologer['experience'] }}</h3>
                        <small class="text-muted">Experience</small>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 text-primary mb-0">{{ $astrologer['consultations'] }}</h3>
                        <small class="text-muted">Consultations</small>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 text-primary mb-0">⭐ {{ $astrologer['rating'] }}</h3>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-3">Languages</h5>
                    <p>{{ implode(', ', $astrologer['languages']) }}</p>
                </div>

                <a href="{{ route('contact') }}" class="btn btn-primary w-100">Book Consultation</a>
            </div>

            <div class="col-lg-8">
                <h3 class="mb-4">About Me</h3>
                <p class="mb-4">{{ $astrologer['about'] }}</p>

                <h3 class="mb-4">Qualifications</h3>
                <ul class="mb-4">
                    @foreach($astrologer['qualifications'] as $qualification)
                    <li class="mb-2">{{ $qualification }}</li>
                    @endforeach
                </ul>

                <h3 class="mb-4">Areas of Expertise</h3>
                <div class="row mb-4">
                    @foreach($astrologer['specializations'] as $specialization)
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h5 class="mb-2">{{ $specialization['title'] }}</h5>
                            <p class="mb-0 text-muted">{{ $specialization['description'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h3 class="mb-4">Achievements & Recognition</h3>
                <ul>
                    @foreach($astrologer['achievements'] as $achievement)
                    <li class="mb-2">{{ $achievement }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <h3 class="mb-4">Ready to Transform Your Life?</h3>
        <p class="lead mb-4">Book a personalized consultation and discover what the stars have in store for you</p>
        <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">Schedule Consultation</a>
    </div>
</div>
@endsection
