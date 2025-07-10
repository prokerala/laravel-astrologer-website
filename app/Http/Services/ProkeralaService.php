<?php

declare(strict_types=1);

namespace App\Http\Services;

use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Profile;
use Prokerala\Api\Astrology\Service\Panchang;
use Prokerala\Api\Astrology\Service\Chart;
use Prokerala\Api\Astrology\Service\Kundli;
use Prokerala\Api\Astrology\Service\KundliMatching;
use Prokerala\Api\Astrology\Service\PlanetPosition;
use Prokerala\Api\Horoscope\Service\DailyPredictionAdvanced;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Authentication\OAuth2;
use Http\Discovery\Psr18Client as PsrHttpClient;
use Http\Discovery\Psr17Factory;
use Prokerala\Common\Api\Exception\ValidationException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use DateTimeImmutable;
use DateTimeZone;
use App\Models\Setting;
use App\Exceptions\ServiceUnavailableException;

class ProkeralaService
{
    private $client;

    public function __construct()
    {
        $clientId = Setting::get('prokerala_client_id');
        $clientSecret = Setting::get('prokerala_client_secret');
        
        if (!$clientId || !$clientSecret) {
            throw new ServiceUnavailableException(
                'Astrology service is temporarily unavailable. Please try again later or contact support.',
                'Prokerala API credentials are not configured. Admin needs to configure them in Settings.'
            );
        }

        $psr17Factory = new Psr17Factory();
        $httpClient = new PsrHttpClient();

        // Create a simple cache implementation
        $cache = new Psr16Cache(new FilesystemAdapter('prokerala', 3600, storage_path('cache')));

        $authClient = new OAuth2($clientId, $clientSecret, $httpClient, $psr17Factory, $psr17Factory, $cache);
        $this->client = new Client($authClient, $httpClient, $psr17Factory);
    }

    public function getBirthChart($datetime, $latitude, $longitude, $timezone)
    {
        try {
            $dateTime = new DateTimeImmutable($datetime, new DateTimeZone($timezone));
            $location = new Location($latitude, $longitude);

            $chartService = new Chart($this->client);
            $planetPositionService = new PlanetPosition($this->client);

            // Get the chart SVG
            $chartSvg = $chartService->process(
                $location,
                $dateTime,
                'rasi', // Chart type
                'north-indian', // Chart style
                'en'
            );

            // Get planet positions
            $planetPositions = $planetPositionService->process(
                $location,
                $dateTime,
                null,
                'en'
            );

            return [
                'success' => true,
                'data' => [
                    'planet_positions' => $this->formatBirthChartPlanets($planetPositions),
                    'chart_svg' => $chartSvg,
                    'ascendant' => $this->getAscendant($planetPositions),
                    'moon_sign' => $this->getMoonSign($planetPositions),
                    'sun_sign' => $this->getSunSign($planetPositions)
                ]
            ];
        } catch (QuotaExceededException $e) {
            return ['success' => false, 'error' => 'API quota exceeded'];
        } catch (RateLimitExceededException $e) {
            return ['success' => false, 'error' => 'Rate limit exceeded. Please try again later.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getKundli($datetime, $latitude, $longitude, $timezone)
    {
        try {
            $dateTime = new DateTimeImmutable($datetime, new DateTimeZone($timezone));
            $location = new Location($latitude, $longitude);

            $kundliService = new Kundli($this->client);
            $chartService = new Chart($this->client);

            $kundliResult = $kundliService->process(
                $location,
                $dateTime,
                true, // detailed report
                'en'
            );

            $chartSvg = $chartService->process(
                $location,
                $dateTime,
                'rasi',
                'north-indian',
                'en'
            );

            return [
                'success' => true,
                'data' => [
                    'ascendant' => $kundliResult->getNakshatraDetails()->getChandraRasi()->getName(),
                    'moon_sign' => $kundliResult->getNakshatraDetails()->getChandraRasi()->getName(),
                    'sun_sign' => $kundliResult->getNakshatraDetails()->getSooryaRasi()->getName(),
                    'nakshatra' => $kundliResult->getNakshatraDetails()->getNakshatra()->getName(),
                    'planets' => $this->formatKundliPlanets($kundliResult),
                    'chart_svg' => $chartSvg,
                    'mangal_dosha' => [
                        'has_dosha' => $kundliResult->getMangalDosha()->hasDosha(),
                        'description' => $kundliResult->getMangalDosha()->getDescription()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getPanchang($date, $latitude, $longitude, $timezone)
    {
        try {
            $dateTime = new DateTimeImmutable($date . ' 06:00:00', new DateTimeZone($timezone));
            $location = new Location($latitude, $longitude);

            $panchangService = new Panchang($this->client);
            $panchang = $panchangService->process(
                $location,
                $dateTime,
                true, // detailed report
                'en'
            );

            // Get first items from arrays
            $tithi = $panchang->getTithi()[0] ?? null;
            $nakshatra = $panchang->getNakshatra()[0] ?? null;
            $karana = $panchang->getKarana()[0] ?? null;
            $yoga = $panchang->getYoga()[0] ?? null;

            return [
                'success' => true,
                'data' => [
                    'tithi' => $tithi ? [
                        'name' => $tithi->getName(),
                        'start' => $tithi->getStart()->format('H:i'),
                        'end' => $tithi->getEnd()->format('H:i')
                    ] : null,
                    'nakshatra' => $nakshatra ? [
                        'name' => $nakshatra->getName(),
                        'start' => $nakshatra->getStart()->format('H:i'),
                        'end' => $nakshatra->getEnd()->format('H:i')
                    ] : null,
                    'yoga' => $yoga ? [
                        'name' => $yoga->getName(),
                        'start' => $yoga->getStart()->format('H:i'),
                        'end' => $yoga->getEnd()->format('H:i')
                    ] : null,
                    'karana' => $karana ? [
                        'name' => $karana->getName(),
                        'start' => $karana->getStart()->format('H:i'),
                        'end' => $karana->getEnd()->format('H:i')
                    ] : null,
                    'vaara' => $panchang->getVaara(),
                    'sunrise' => $panchang->getSunrise()->format('H:i'),
                    'sunset' => $panchang->getSunset()->format('H:i'),
                    'moonrise' => $panchang->getMoonrise() ? $panchang->getMoonrise()->format('H:i') : 'N/A',
                    'moonset' => $panchang->getMoonset() ? $panchang->getMoonset()->format('H:i') : 'N/A',
                    'auspicious_timings' => $this->formatMuhurta($panchang->getAuspiciousPeriod()),
                    'inauspicious_timings' => $this->formatMuhurta($panchang->getInauspiciousPeriod())
                ]
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getCompatibility($params)
    {
        try {
            // Boy's profile
            $boyDateTime = new DateTimeImmutable($params['boy_dob'] . ' ' . $params['boy_tob'], new DateTimeZone($params['boy_tz']));
            $boyLocation = new Location($params['boy_lat'], $params['boy_lon']);
            $boyProfile = new Profile($boyLocation, $boyDateTime);

            // Girl's profile
            $girlDateTime = new DateTimeImmutable($params['girl_dob'] . ' ' . $params['girl_tob'], new DateTimeZone($params['girl_tz']));
            $girlLocation = new Location($params['girl_lat'], $params['girl_lon']);
            $girlProfile = new Profile($girlLocation, $girlDateTime);

            $matchingService = new KundliMatching($this->client);
            $compatibility = $matchingService->process(
                $girlProfile,
                $boyProfile,
                true, // detailed report
                'en'
            );

            $gunaMilan = $compatibility->getGunaMilan();
            $totalPoints = $gunaMilan->getTotalPoints();
            $maxPoints = $gunaMilan->getMaximumPoints();

            return [
                'success' => true,
                'data' => [
                    'total_points' => $totalPoints,
                    'maximum_points' => $maxPoints,
                    'compatibility_percentage' => round(($totalPoints / $maxPoints) * 100, 2),
                    'message' => $this->getCompatibilityMessage($totalPoints),
                    'compatibility_details' => $this->formatCompatibilityDetails($gunaMilan),
                    'girl_mangal_dosha' => [
                        'has_dosha' => $compatibility->getGirlMangalDoshaDetails()->hasDosha(),
                        'description' => $compatibility->getGirlMangalDoshaDetails()->getDescription()
                    ],
                    'boy_mangal_dosha' => [
                        'has_dosha' => $compatibility->getBoyMangalDoshaDetails()->hasDosha(),
                        'description' => $compatibility->getBoyMangalDoshaDetails()->getDescription()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getDailyHoroscope($sign)
    {
        try {
            $dailyPredictionService = new DailyPredictionAdvanced($this->client);
            $horoscopeResponse = $dailyPredictionService->process(
                new DateTimeImmutable('today'),
                $sign,
                'all' // type parameter for all predictions
            );

            // Get the daily predictions array
            $dailyPredictions = $horoscopeResponse->getDailyPredictions();

            if (empty($dailyPredictions)) {
                return ['success' => false, 'error' => 'No predictions available'];
            }

            // Get the first (and usually only) prediction for the requested sign
            $dailyHoroscope = $dailyPredictions[0];

            // Extract predictions by type
            $predictions = $dailyHoroscope->getPredictions();
            $predictionsByType = [];

            foreach ($predictions as $prediction) {
                $type = strtolower($prediction->getType());
                $predictionsByType[$type] = [
                    'prediction' => $prediction->getPrediction(),
                    'seek' => $prediction->getSeek(),
                    'challenge' => $prediction->getChallenge(),
                    'insight' => $prediction->getInsight()
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'sign' => $dailyHoroscope->getSign()->getName(),
                    'sign_info' => [
                        'modality' => $dailyHoroscope->getSignInfo()->getModality(),
                        'triplicity' => $dailyHoroscope->getSignInfo()->getTriplicity(),
                        'quadruplicity' => $dailyHoroscope->getSignInfo()->getQuadruplicity(),
                        'symbol' => $dailyHoroscope->getSignInfo()->getUnicodeSymbol()
                    ],
                    'predictions' => $predictionsByType,
                    'aspects' => $this->formatAspects($dailyHoroscope->getAspects()),
                    'transits' => $this->formatTransits($dailyHoroscope->getTransits())
                ]
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function formatBirthChartPlanets($planetPositions)
    {
        $formatted = [];
        $positions = $planetPositions->getPlanetPosition();

        foreach ($positions as $planet) {
            $formatted[] = [
                'name' => $planet->getName(),
                'sign' => $planet->getRasi()->getName(),
                'degree' => round($planet->getDegree(), 2),
                'house' => $planet->getPosition(),
                'is_retrograde' => $planet->isRetrograde()
            ];
        }

        return $formatted;
    }

    private function getAscendant($planetPositions)
    {
        $positions = $planetPositions->getPlanetPosition();
        foreach ($positions as $planet) {
            if ($planet->getId() === 100) { // Ascendant ID
                return $planet->getRasi()->getName();
            }
        }
        return 'N/A';
    }

    private function getMoonSign($planetPositions)
    {
        $positions = $planetPositions->getPlanetPosition();
        foreach ($positions as $planet) {
            if ($planet->getId() === 1) { // Moon ID
                return $planet->getRasi()->getName();
            }
        }
        return 'N/A';
    }

    private function getSunSign($planetPositions)
    {
        $positions = $planetPositions->getPlanetPosition();
        foreach ($positions as $planet) {
            if ($planet->getId() === 0) { // Sun ID
                return $planet->getRasi()->getName();
            }
        }
        return 'N/A';
    }

    private function formatKundliPlanets($kundliResult)
    {
        // Extract planet information from yoga details if available
        $planets = [];

        // Add basic planet info from nakshatra details
        $planets[] = [
            'name' => 'Moon',
            'sign' => $kundliResult->getNakshatraDetails()->getChandraRasi()->getName(),
            'nakshatra' => $kundliResult->getNakshatraDetails()->getNakshatra()->getName(),
            'pada' => $kundliResult->getNakshatraDetails()->getNakshatra()->getPada()
        ];

        $planets[] = [
            'name' => 'Sun',
            'sign' => $kundliResult->getNakshatraDetails()->getSooryaRasi()->getName(),
            'nakshatra' => 'N/A',
            'pada' => 'N/A'
        ];

        return $planets;
    }

    private function formatMuhurta($periods)
    {
        $formatted = [];
        foreach ($periods as $muhurat) {
            $periodList = $muhurat->getPeriod();
            foreach ($periodList as $period) {
                $formatted[] = [
                    'name' => $muhurat->getName(),
                    'start' => $period->getStart()->format('H:i'),
                    'end' => $period->getEnd()->format('H:i')
                ];
            }
        }
        return $formatted;
    }

    private function formatCompatibilityDetails($gunaMilan)
    {
        $formatted = [];
        $gunas = $gunaMilan->getGuna();

        foreach ($gunas as $guna) {
            $formatted[] = [
                'name' => $guna->getName(),
                'points' => $guna->getObtainedPoints(),
                'max_points' => $guna->getMaximumPoints(),
                'description' => $guna->getDescription()
            ];
        }
        return $formatted;
    }

    private function getCompatibilityMessage($points)
    {
        if ($points >= 28) {
            return "Excellent compatibility! This is a highly recommended match with strong harmony in all aspects.";
        } elseif ($points >= 21) {
            return "Good compatibility! This match has positive potential with minor adjustments needed.";
        } elseif ($points >= 18) {
            return "Average compatibility. This match can work with understanding and compromise from both partners.";
        } else {
            return "Below average compatibility. Significant challenges may arise in this relationship.";
        }
    }

    private function formatAspects($aspects)
    {
        $formatted = [];
        foreach ($aspects as $aspect) {
            $formatted[] = [
                'planet_one' => $aspect->getPlanetOne()->getName(),
                'planet_two' => $aspect->getPlanetTwo()->getName(),
                'aspect' => $aspect->getAspect()->getName(),
                'effect' => $aspect->getEffect()
            ];
        }
        return $formatted;
    }

    private function formatTransits($transits)
    {
        $formatted = [];
        foreach ($transits as $transit) {
            $formatted[] = [
                'name' => $transit->getName(),
                'zodiac' => $transit->getZodiac()->getName(),
                'house' => $transit->getHouseNumber(),
                'is_retrograde' => $transit->getIsRetrograde()
            ];
        }
        return $formatted;
    }
}
