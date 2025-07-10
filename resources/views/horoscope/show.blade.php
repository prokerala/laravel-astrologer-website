@extends('layouts.app')

@section('title', ucfirst($sign) . ' Daily Horoscope - Divine Astrology')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-4">
                <h1 class="mb-3">{{ ucfirst($sign) }} Daily Horoscope</h1>
                <p class="text-muted">{{ now()->format('l, F d, Y') }}</p>
            </div>

            @if($horoscope && isset($horoscope['data']))
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    @if(isset($horoscope['data']['predictions']))

                    @if(isset($horoscope['data']['predictions']['general']))
                    <div class="mb-4">
                        <h3 class="h5 mb-3"><i class="fas fa-star me-2 text-primary"></i>Today's Overview</h3>
                        <p class="lead">{{ $horoscope['data']['predictions']['general']['prediction'] }}</p>
                        @if($horoscope['data']['predictions']['general']['insight'])
                        <div class="alert alert-info mt-3">
                            <strong>Insight:</strong> {{ $horoscope['data']['predictions']['general']['insight'] }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="row">
                        @if(isset($horoscope['data']['predictions']['love']))
                        <div class="col-md-6 mb-4">
                            <div class="p-4 bg-light rounded h-100">
                                <h4 class="h6 text-danger mb-3"><i class="fas fa-heart me-2"></i>Love & Relationships</h4>
                                <p class="mb-2">{{ $horoscope['data']['predictions']['love']['prediction'] }}</p>
                                @if($horoscope['data']['predictions']['love']['seek'])
                                <p class="mb-0 small"><strong>Seek:</strong> {{ $horoscope['data']['predictions']['love']['seek'] }}</p>
                                @endif
                                @if($horoscope['data']['predictions']['love']['challenge'])
                                <p class="mb-0 small text-muted"><strong>Challenge:</strong> {{ $horoscope['data']['predictions']['love']['challenge'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($horoscope['data']['predictions']['career']))
                        <div class="col-md-6 mb-4">
                            <div class="p-4 bg-light rounded h-100">
                                <h4 class="h6 text-success mb-3"><i class="fas fa-briefcase me-2"></i>Career & Work</h4>
                                <p class="mb-2">{{ $horoscope['data']['predictions']['career']['prediction'] }}</p>
                                @if($horoscope['data']['predictions']['career']['seek'])
                                <p class="mb-0 small"><strong>Seek:</strong> {{ $horoscope['data']['predictions']['career']['seek'] }}</p>
                                @endif
                                @if($horoscope['data']['predictions']['career']['challenge'])
                                <p class="mb-0 small text-muted"><strong>Challenge:</strong> {{ $horoscope['data']['predictions']['career']['challenge'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($horoscope['data']['predictions']['health']))
                        <div class="col-md-6 mb-4">
                            <div class="p-4 bg-light rounded h-100">
                                <h4 class="h6 text-info mb-3"><i class="fas fa-heartbeat me-2"></i>Health & Wellness</h4>
                                <p class="mb-2">{{ $horoscope['data']['predictions']['health']['prediction'] }}</p>
                                @if($horoscope['data']['predictions']['health']['seek'])
                                <p class="mb-0 small"><strong>Seek:</strong> {{ $horoscope['data']['predictions']['health']['seek'] }}</p>
                                @endif
                                @if($horoscope['data']['predictions']['health']['challenge'])
                                <p class="mb-0 small text-muted"><strong>Challenge:</strong> {{ $horoscope['data']['predictions']['health']['challenge'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>

                    @endif
                </div>
            </div>

            @if(isset($horoscope['data']['sign_info']))
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="h5 mb-3">About {{ $horoscope['data']['sign'] }}</h4>
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <span class="display-1">{{ $horoscope['data']['sign_info']['symbol'] }}</span>
                        </div>
                        <div class="col-md-9">
                            <p><strong>Modality:</strong> {{ $horoscope['data']['sign_info']['modality'] }}</p>
                            <p><strong>Element:</strong> {{ $horoscope['data']['sign_info']['triplicity'] }}</p>
                            <p><strong>Quality:</strong> {{ $horoscope['data']['sign_info']['quadruplicity'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($horoscope['data']['transits']) && count($horoscope['data']['transits']) > 0)
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="h5 mb-3">Current Planetary Transits</h4>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Planet</th>
                                    <th>Zodiac</th>
                                    <th>House</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($horoscope['data']['transits'] as $transit)
                                <tr>
                                    <td>{{ $transit['name'] }}</td>
                                    <td>{{ $transit['zodiac'] }}</td>
                                    <td>{{ $transit['house'] }}</td>
                                    <td>
                                        @if($transit['is_retrograde'])
                                            <span class="badge bg-warning">Retrograde</span>
                                        @else
                                            <span class="badge bg-success">Direct</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @else
            <div class="alert alert-info">
                <p class="mb-0">Horoscope data is currently unavailable. Please try again later.</p>
            </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('horoscope.daily') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to All Signs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
