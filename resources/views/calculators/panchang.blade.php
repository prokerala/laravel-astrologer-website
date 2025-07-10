@extends('layouts.app')

@section('title', 'Daily Panchang - Divine Astrology')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-4">Daily Panchang</h1>
            <p class="text-center text-muted mb-5">Check today's auspicious timings and planetary positions</p>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('calculator.panchang.calculate') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="date" class="form-label">Select Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                   id="date" name="date" value="{{ old('date', $input['date'] ?? date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text"
                                   class="form-control prokerala-location-input @error('location') is-invalid @enderror"
                                   id="location"
                                   name="location"
                                   placeholder="Enter city name"
                                   value="{{ old('location', $input['location'] ?? 'Delhi, India') }}"
                                   required>
                            <small class="text-muted">Start typing and select your location from the list</small>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Get Panchang</button>
                    </form>
                </div>
            </div>

            @if(isset($result) && $result['success'])
            <div class="mt-5">
                <h2 class="text-center mb-4">Panchang for {{ \Carbon\Carbon::parse($input['date'])->format('d M Y') }}</h2>

                @if(isset($result['data']))
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">Basic Panchang</h3>
                                <p><strong>Weekday:</strong> {{ $result['data']['vaara'] ?? 'N/A' }}</p>
                                @if($result['data']['tithi'])
                                    <p><strong>Tithi:</strong> {{ $result['data']['tithi']['name'] }} ({{ $result['data']['tithi']['start'] }} - {{ $result['data']['tithi']['end'] }})</p>
                                @endif
                                @if($result['data']['nakshatra'])
                                    <p><strong>Nakshatra:</strong> {{ $result['data']['nakshatra']['name'] }} ({{ $result['data']['nakshatra']['start'] }} - {{ $result['data']['nakshatra']['end'] }})</p>
                                @endif
                                @if($result['data']['yoga'])
                                    <p><strong>Yoga:</strong> {{ $result['data']['yoga']['name'] }} ({{ $result['data']['yoga']['start'] }} - {{ $result['data']['yoga']['end'] }})</p>
                                @endif
                                @if($result['data']['karana'])
                                    <p><strong>Karana:</strong> {{ $result['data']['karana']['name'] }} ({{ $result['data']['karana']['start'] }} - {{ $result['data']['karana']['end'] }})</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">Sun & Moon Timings</h3>
                                <p><strong>Sunrise:</strong> {{ $result['data']['sunrise'] ?? 'N/A' }}</p>
                                <p><strong>Sunset:</strong> {{ $result['data']['sunset'] ?? 'N/A' }}</p>
                                <p><strong>Moonrise:</strong> {{ $result['data']['moonrise'] ?? 'N/A' }}</p>
                                <p><strong>Moonset:</strong> {{ $result['data']['moonset'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @if(isset($result['data']['auspicious_timings']) && count($result['data']['auspicious_timings']) > 0)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">Auspicious Timings</h3>
                                @foreach($result['data']['auspicious_timings'] ?? [] as $timing)
                                <p class="mb-2"><strong>{{ $timing['name'] }}:</strong> {{ $timing['start'] }} - {{ $timing['end'] }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($result['data']['inauspicious_timings']) && count($result['data']['inauspicious_timings']) > 0)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">Inauspicious Timings</h3>
                                @foreach($result['data']['inauspicious_timings'] ?? [] as $timing)
                                <p class="mb-2"><strong>{{ $timing['name'] }}:</strong> {{ $timing['start'] }} - {{ $timing['end'] }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
const PK_API_CLIENT_ID = '{{ config("services.prokerala.client_id") }}';
(function () {
    function loadScript(cb) {
        var script = document.createElement('script');
        script.src = 'https://client-api.prokerala.com/static/js/location.min.js';
        script.onload = cb;
        script.async = 1;
        document.head.appendChild(script);
    }
    function createInput(name, value) {
        const input = document.createElement('input');
        input.name = name;
        input.type = 'hidden';
        return input;
    }
    function initWidget(input) {
        const form = input.form;
        const inputPrefix = input.dataset.locationInputPrefix ? input.dataset.locationInputPrefix : '';
        const coordinates = createInput(inputPrefix +'coordinates');
        const timezone = createInput(inputPrefix +'timezone');
        form.appendChild(coordinates);
        form.appendChild(timezone);
        new LocationSearch(input, function (data) {
            coordinates.value = `${data.latitude},${data.longitude}`;
            timezone.value = data.timezone;
            input.setCustomValidity('');
        }, {clientId: PK_API_CLIENT_ID, persistKey: `${inputPrefix}loc`});
        input.addEventListener('change', function (e) {
            input.setCustomValidity('Please select a location from the suggestions list');
        });
    }
    loadScript(function() {
        let location = document.querySelectorAll('.prokerala-location-input');
        Array.from(location).map(initWidget);
    });
})();
</script>
@endpush
@endsection
