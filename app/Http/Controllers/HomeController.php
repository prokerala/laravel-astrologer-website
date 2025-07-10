<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = [
            [
                'title' => 'Birth Chart Analysis',
                'description' => 'Get detailed insights about your personality and life path',
                'icon' => 'fas fa-chart-pie',
                'route' => 'calculator.birth-chart'
            ],
            [
                'title' => 'Kundli Generation',
                'description' => 'Generate your complete Vedic birth chart',
                'icon' => 'fas fa-star',
                'route' => 'calculator.kundli'
            ],
            [
                'title' => 'Daily Panchang',
                'description' => 'Check auspicious timings and planetary positions',
                'icon' => 'fas fa-calendar-alt',
                'route' => 'calculator.panchang'
            ],
            [
                'title' => 'Compatibility Check',
                'description' => 'Analyze relationship compatibility using Vedic astrology',
                'icon' => 'fas fa-heart',
                'route' => 'calculator.compatibility'
            ]
        ];

        $astrologer = [
            'name' => 'Dr. Rajesh Kumar Sharma',
            'title' => 'Vedic Astrologer & Spiritual Guide',
            'experience' => '25+ Years of Experience',
            'specialties' => ['Vedic Astrology', 'Numerology', 'Vastu Shastra', 'Gemstone Consultation'],
            'photo' => '/images/astrologer-profile.png'
        ];

        return view('home', compact('services', 'astrologer'));
    }
}
