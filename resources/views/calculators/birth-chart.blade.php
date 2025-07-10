@extends('layouts.app')

@section('title', 'Birth Chart Calculator - Divine Astrology')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-4">Birth Chart Calculator</h1>
            <p class="text-center text-muted mb-5">Enter your birth details to generate your personalized astrological birth chart</p>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('calculator.birth-chart.calculate') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $input['name'] ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                       id="date" name="date" value="{{ old('date', $input['date'] ?? '') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Birth Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror"
                                       id="time" name="time" value="{{ old('time', $input['time'] ?? '') }}" required>
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Birth Place</label>
                            <input type="text"
                                   class="form-control prokerala-location-input @error('location') is-invalid @enderror"
                                   id="location"
                                   name="location"
                                   placeholder="Enter city name"
                                   value="{{ old('location', $input['location'] ?? '') }}"
                                   required>
                            <small class="text-muted">Start typing and select your birth place from the list</small>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Generate Birth Chart</button>
                    </form>
                </div>
            </div>

            @if(isset($result) && $result['success'])
            <div class="mt-5">
                <h2 class="text-center mb-4">Your Birth Chart Analysis</h2>

                @if(isset($result['data']))
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">Basic Details</h3>
                                <p><strong>Ascendant:</strong> {{ $result['data']['ascendant'] ?? 'N/A' }}</p>
                                <p><strong>Moon Sign:</strong> {{ $result['data']['moon_sign'] ?? 'N/A' }}</p>
                                <p><strong>Sun Sign:</strong> {{ $result['data']['sun_sign'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">Planetary Positions</h3>
                                @if(isset($result['data']['planet_positions']) && count($result['data']['planet_positions']) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Planet</th>
                                                <th>Sign</th>
                                                <th>Degree</th>
                                                <th>House</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($result['data']['planet_positions'] as $planet)
                                            <tr>
                                                <td>
                                                    {{ $planet['name'] }}
                                                    @if($planet['is_retrograde'])
                                                        <span class="text-danger" title="Retrograde">(R)</span>
                                                    @endif
                                                </td>
                                                <td>{{ $planet['sign'] }}</td>
                                                <td>{{ $planet['degree'] }}°</td>
                                                <td>{{ $planet['house'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <p class="text-muted">Planet position details not available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($result['data']['chart_svg']) && $result['data']['chart_svg'])
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Birth Chart</h3>
                        <div class="text-center chart-container">
                            {!! $result['data']['chart_svg'] !!}
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.chart-container svg {
    max-width: 100%;
    height: auto;
}
</style>
@endpush

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
