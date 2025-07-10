<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProkeralaService;

class HoroscopeController extends Controller
{
    private $prokeralaService;

    public function __construct(ProkeralaService $prokeralaService)
    {
        $this->prokeralaService = $prokeralaService;
    }

    public function daily()
    {
        $signs = [
            'aries' => ['name' => 'Aries', 'dates' => 'Mar 21 - Apr 19', 'icon' => '♈'],
            'taurus' => ['name' => 'Taurus', 'dates' => 'Apr 20 - May 20', 'icon' => '♉'],
            'gemini' => ['name' => 'Gemini', 'dates' => 'May 21 - Jun 20', 'icon' => '♊'],
            'cancer' => ['name' => 'Cancer', 'dates' => 'Jun 21 - Jul 22', 'icon' => '♋'],
            'leo' => ['name' => 'Leo', 'dates' => 'Jul 23 - Aug 22', 'icon' => '♌'],
            'virgo' => ['name' => 'Virgo', 'dates' => 'Aug 23 - Sep 22', 'icon' => '♍'],
            'libra' => ['name' => 'Libra', 'dates' => 'Sep 23 - Oct 22', 'icon' => '♎'],
            'scorpio' => ['name' => 'Scorpio', 'dates' => 'Oct 23 - Nov 21', 'icon' => '♏'],
            'sagittarius' => ['name' => 'Sagittarius', 'dates' => 'Nov 22 - Dec 21', 'icon' => '♐'],
            'capricorn' => ['name' => 'Capricorn', 'dates' => 'Dec 22 - Jan 19', 'icon' => '♑'],
            'aquarius' => ['name' => 'Aquarius', 'dates' => 'Jan 20 - Feb 18', 'icon' => '♒'],
            'pisces' => ['name' => 'Pisces', 'dates' => 'Feb 19 - Mar 20', 'icon' => '♓']
        ];

        return view('horoscope.daily', compact('signs'));
    }

    public function show($sign)
    {
        $validSigns = ['aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo',
                      'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces'];

        if (!in_array($sign, $validSigns)) {
            abort(404);
        }

        $horoscope = $this->prokeralaService->getDailyHoroscope($sign);

        if (!$horoscope['success']) {
            return back()->with('error', $horoscope['error']);
        }

        return view('horoscope.show', [
            'sign' => $sign,
            'horoscope' => $horoscope
        ]);
    }
}
