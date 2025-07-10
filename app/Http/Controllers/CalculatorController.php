<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProkeralaService;

class CalculatorController extends Controller
{
    public function __construct(private ProkeralaService $prokeralaService)
    {
    }

    public function birthChart()
    {
        return view('calculators.birth-chart');
    }

    public function calculateBirthChart(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'coordinates' => 'required|string',
            'timezone' => 'required|string'
        ]);


        // Parse coordinates
        $coords = explode(',', $request->coordinates);
        $latitude = (float) $coords[0];
        $longitude = (float) $coords[1];

        $datetime = $request->date . ' ' . $request->time;

        $result = $this->prokeralaService->getBirthChart(
            $datetime,
            $latitude,
            $longitude,
            $request->timezone
        );

        if (!$result['success']) {
            return back()->with('error', $result['error'])->withInput();
        }

        return view('calculators.birth-chart', [
            'result' => $result,
            'input' => $request->all()
        ]);
    }

    public function kundli()
    {
        return view('calculators.kundli');
    }


    public function calculateKundli(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'coordinates' => 'required|string',
            'timezone' => 'required|string'
        ]);

        // Parse coordinates
        $coords = explode(',', $request->coordinates);
        $latitude = (float) $coords[0];
        $longitude = (float) $coords[1];

        $datetime = $request->date . ' ' . $request->time;

        $result = $this->prokeralaService->getKundli(
            $datetime,
            $latitude,
            $longitude,
            $request->timezone
        );

        if (!$result['success']) {
            return back()->with('error', $result['error'])->withInput();
        }

        return view('calculators.kundli', [
            'result' => $result,
            'input' => $request->all()
        ]);
    }

    public function panchang()
    {
        return view('calculators.panchang');
    }

    public function calculatePanchang(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'location' => 'required|string',
            'coordinates' => 'required|string',
            'timezone' => 'required|string'
        ]);

        // Parse coordinates
        $coords = explode(',', $request->coordinates);
        $latitude = (float) $coords[0];
        $longitude = (float) $coords[1];

        $result = $this->prokeralaService->getPanchang(
            $request->date,
            $latitude,
            $longitude,
            $request->timezone
        );

        if (!$result['success']) {
            return back()->with('error', $result['error'])->withInput();
        }

        return view('calculators.panchang', [
            'result' => $result,
            'input' => $request->all()
        ]);
    }


    public function compatibility()
    {
        return view('calculators.compatibility');
    }

    public function calculateCompatibility(Request $request)
    {
        $request->validate([
            'boy_name' => 'required|string',
            'boy_date' => 'required|date',
            'boy_time' => 'required',
            'boy_location' => 'required|string',
            'boy_coordinates' => 'required|string',
            'boy_timezone' => 'required|string',
            'girl_name' => 'required|string',
            'girl_date' => 'required|date',
            'girl_time' => 'required',
            'girl_location' => 'required|string',
            'girl_coordinates' => 'required|string',
            'girl_timezone' => 'required|string'
        ]);

        // Parse boy coordinates
        $boyCoords = explode(',', $request->boy_coordinates);
        $boyLat = (float) $boyCoords[0];
        $boyLon = (float) $boyCoords[1];

        // Parse girl coordinates
        $girlCoords = explode(',', $request->girl_coordinates);
        $girlLat = (float) $girlCoords[0];
        $girlLon = (float) $girlCoords[1];

        $params = [
            'boy_dob' => $request->boy_date,
            'boy_tob' => $request->boy_time,
            'boy_lat' => $boyLat,
            'boy_lon' => $boyLon,
            'boy_tz' => $request->boy_timezone,
            'girl_dob' => $request->girl_date,
            'girl_tob' => $request->girl_time,
            'girl_lat' => $girlLat,
            'girl_lon' => $girlLon,
            'girl_tz' => $request->girl_timezone
        ];

        $result = $this->prokeralaService->getCompatibility($params);

        if (!$result['success']) {
            return back()->with('error', $result['error'])->withInput();
        }

        return view('calculators.compatibility', [
            'result' => $result,
            'input' => $request->all()
        ]);
    }
}
