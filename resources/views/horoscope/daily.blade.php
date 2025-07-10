@extends('layouts.app')

@section('title', 'Daily Horoscope - Divine Astrology')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Daily Horoscope</h1>
    <p class="text-center text-muted mb-5">Select your zodiac sign to read today's horoscope</p>
    
    <div class="zodiac-grid">
        @foreach($signs as $key => $sign)
        <a href="{{ route('horoscope.show', $key) }}" class="zodiac-card">
            <div class="zodiac-icon">{{ $sign['icon'] }}</div>
            <h5 class="mb-1">{{ $sign['name'] }}</h5>
            <small class="text-muted">{{ $sign['dates'] }}</small>
        </a>
        @endforeach
    </div>
</div>
@endsection

