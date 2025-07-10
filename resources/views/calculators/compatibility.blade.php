@extends('layouts.app')

@section('title', 'Compatibility Calculator - Divine Astrology')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="text-center mb-4">Compatibility Calculator</h1>
            <p class="text-center text-muted mb-5">Check your astrological compatibility with your partner</p>

            <form method="POST" action="{{ route('calculator.compatibility.calculate') }}">
                @csrf

                <div class="row">
                    <!-- Boy's Details -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Partner 1 Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="boy_name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('boy_name') is-invalid @enderror"
                                           id="boy_name" name="boy_name" value="{{ old('boy_name', $input['boy_name'] ?? '') }}" required>
                                    @error('boy_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="boy_date" class="form-label">Birth Date</label>
                                        <input type="date" class="form-control @error('boy_date') is-invalid @enderror"
                                               id="boy_date" name="boy_date" value="{{ old('boy_date', $input['boy_date'] ?? '') }}" required>
                                        @error('boy_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="boy_time" class="form-label">Birth Time</label>
                                        <input type="time" class="form-control @error('boy_time') is-invalid @enderror"
                                               id="boy_time" name="boy_time" value="{{ old('boy_time', $input['boy_time'] ?? '') }}" required>
                                        @error('boy_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="boy_location" class="form-label">Birth Place</label>
                                    <input type="text"
                                           class="form-control prokerala-location-input @error('boy_location') is-invalid @enderror"
                                           id="boy_location"
                                           name="boy_location"
                                           placeholder="Enter city name"
                                           data-location-input-prefix="boy_"
                                           value="{{ old('boy_location', $input['boy_location'] ?? '') }}"
                                           required>
                                    <small class="text-muted">Start typing and select from the list</small>
                                    @error('boy_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Girl's Details -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="mb-0">Partner 2 Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="girl_name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('girl_name') is-invalid @enderror"
                                           id="girl_name" name="girl_name" value="{{ old('girl_name', $input['girl_name'] ?? '') }}" required>
                                    @error('girl_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="girl_date" class="form-label">Birth Date</label>
                                        <input type="date" class="form-control @error('girl_date') is-invalid @enderror"
                                               id="girl_date" name="girl_date" value="{{ old('girl_date', $input['girl_date'] ?? '') }}" required>
                                        @error('girl_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
<label for="girl_time" class="form-label">Birth Time</label>
                                       <input type="time" class="form-control @error('girl_time') is-invalid @enderror"
                                              id="girl_time" name="girl_time" value="{{ old('girl_time', $input['girl_time'] ?? '') }}" required>
                                       @error('girl_time')
                                           <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                   </div>
                               </div>

                               <div class="mb-3">
                                   <label for="girl_location" class="form-label">Birth Place</label>
                                   <input type="text"
                                          class="form-control prokerala-location-input @error('girl_location') is-invalid @enderror"
                                          id="girl_location"
                                          name="girl_location"
                                          placeholder="Enter city name"
                                          data-location-input-prefix="girl_"
                                          value="{{ old('girl_location', $input['girl_location'] ?? '') }}"
                                          required>
                                   <small class="text-muted">Start typing and select from the list</small>
                                   @error('girl_location')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div>
                       </div>
                   </div>
               </div>

               <button type="submit" class="btn btn-primary btn-lg w-100">Check Compatibility</button>
           </form>

           @if(isset($result) && $result['success'])
           <div class="mt-5">
               <h2 class="text-center mb-4">Compatibility Results</h2>

               @if(isset($result['data']))
               <div class="card shadow-sm mb-4">
                   <div class="card-body">
                       <div class="text-center mb-4">
                           <h3 class="display-4 mb-3">{{ $result['data']['total_points'] ?? 0 }}/{{ $result['data']['maximum_points'] ?? 36 }}</h3>
                           <p class="lead">Overall Compatibility Score</p>
                           @if(isset($result['data']['compatibility_percentage']))
                           <div class="progress" style="height: 30px;">
                               <div class="progress-bar bg-{{ $result['data']['compatibility_percentage'] >= 70 ? 'success' : ($result['data']['compatibility_percentage'] >= 50 ? 'warning' : 'danger') }}"
                                    role="progressbar"
                                    style="width: {{ $result['data']['compatibility_percentage'] }}%">
                                   {{ $result['data']['compatibility_percentage'] }}%
                               </div>
                           </div>
                           @endif
                       </div>

                       @if(isset($result['data']['message']))
                       <div class="alert alert-{{ $result['data']['compatibility_percentage'] >= 70 ? 'success' : ($result['data']['compatibility_percentage'] >= 50 ? 'warning' : 'danger') }}">
                           <p class="mb-0">{{ $result['data']['message'] }}</p>
                       </div>
                       @endif
                   </div>
               </div>

               <div class="row">
                   @if(isset($result['data']['girl_mangal_dosha']))
                   <div class="col-md-6 mb-4">
                       <div class="card shadow-sm h-100">
                           <div class="card-body">
                               <h4 class="h5 mb-3">{{ $input['girl_name'] }}'s Mangal Dosha</h4>
                               <div class="alert {{ $result['data']['girl_mangal_dosha']['has_dosha'] ? 'alert-warning' : 'alert-success' }} mb-2">
                                   <strong>Status:</strong> {{ $result['data']['girl_mangal_dosha']['has_dosha'] ? 'Present' : 'Not Present' }}
                               </div>
                               <p class="small">{{ $result['data']['girl_mangal_dosha']['description'] }}</p>
                           </div>
                       </div>
                   </div>
                   @endif

                   @if(isset($result['data']['boy_mangal_dosha']))
                   <div class="col-md-6 mb-4">
                       <div class="card shadow-sm h-100">
                           <div class="card-body">
                               <h4 class="h5 mb-3">{{ $input['boy_name'] }}'s Mangal Dosha</h4>
                               <div class="alert {{ $result['data']['boy_mangal_dosha']['has_dosha'] ? 'alert-warning' : 'alert-success' }} mb-2">
                                   <strong>Status:</strong> {{ $result['data']['boy_mangal_dosha']['has_dosha'] ? 'Present' : 'Not Present' }}
                               </div>
                               <p class="small">{{ $result['data']['boy_mangal_dosha']['description'] }}</p>
                           </div>
                       </div>
                   </div>
                   @endif
               </div>

               @if(isset($result['data']['compatibility_details']) && count($result['data']['compatibility_details']) > 0)
               <div class="card shadow-sm">
                   <div class="card-body">
                       <h3 class="card-title mb-4">Detailed Guna Milan Analysis</h3>
                       <div class="table-responsive">
                           <table class="table table-striped">
                               <thead>
                                   <tr>
                                       <th>Guna</th>
                                       <th>Points Obtained</th>
                                       <th>Maximum Points</th>
                                       <th>Description</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @foreach($result['data']['compatibility_details'] ?? [] as $detail)
                                   <tr>
                                       <td><strong>{{ $detail['name'] }}</strong></td>
                                       <td>
                                           <span class="badge bg-{{ $detail['points'] == $detail['max_points'] ? 'success' : ($detail['points'] > 0 ? 'warning' : 'danger') }}">
                                               {{ $detail['points'] }}
                                           </span>
                                       </td>
                                       <td>{{ $detail['max_points'] }}</td>
                                       <td class="small">{{ $detail['description'] }}</td>
                                   </tr>
                                   @endforeach
                               </tbody>
                           </table>
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
