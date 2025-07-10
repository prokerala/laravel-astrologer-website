<?php

declare(stric_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AstrologerController extends Controller
{
    public function profile()
    {
        $astrologer = [
            'name' => 'Dr. Rajesh Kumar Sharma',
            'title' => 'Vedic Astrologer & Spiritual Guide',
            'photo' => '/images/astrologer-profile.png',
            'experience' => '25+ Years',
            'consultations' => '50,000+',
            'rating' => '4.9',
            'languages' => ['English', 'Hindi', 'Sanskrit'],
            'qualifications' => [
                'Ph.D. in Vedic Astrology from Banaras Hindu University',
                'Master\'s in Sanskrit Literature',
                'Certified Vastu Consultant',
                'Member of Indian Council of Astrological Sciences'
            ],
            'specializations' => [
                [
                    'title' => 'Vedic Astrology',
                    'description' => 'Expert in birth chart analysis, predictions, and remedial measures'
                ],
                [
                    'title' => 'Marriage & Compatibility',
                    'description' => 'Specialized in matchmaking and relationship counseling'
                ],
                [
                    'title' => 'Career Guidance',
                    'description' => 'Helping individuals find their true calling and career path'
                ],
                [
                    'title' => 'Vastu Shastra',
                    'description' => 'Harmonizing living spaces for prosperity and well-being'
                ]
            ],
            'about' => 'With over 25 years of experience in Vedic astrology, Dr. Rajesh Kumar Sharma has guided thousands of individuals towards a more fulfilling life. His deep understanding of ancient Vedic texts combined with a modern approach makes him one of the most sought-after astrologers in the country. He believes in empowering people with knowledge and practical remedies to overcome life\'s challenges.',
            'achievements' => [
                'Awarded "Best Astrologer of the Year" by Astrology Foundation of India (2020)',
                'Featured in leading newspapers and TV channels',
                'Author of 5 books on Vedic astrology and spirituality',
                'Conducted 500+ workshops and seminars worldwide'
            ]
        ];

        return view('astrologer.profile', compact('astrologer'));
    }
}
