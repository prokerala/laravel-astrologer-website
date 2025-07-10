<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $settings = [
                'prokerala_client_id' => Setting::get('prokerala_client_id'),
                'prokerala_client_secret' => Setting::get('prokerala_client_secret'),
                'site_name' => Setting::get('site_name', 'Divine Astrology'),
                'site_description' => Setting::get('site_description', 'Your trusted source for Vedic astrology consultations and personalized horoscope readings.'),
                'contact_email' => Setting::get('contact_email', 'info@example.com'),
                'contact_phone' => Setting::get('contact_phone', '+1 (555) 123-4567'),
                'contact_address' => Setting::get('contact_address', 'New York, USA'),
                'whatsapp_number' => Setting::get('whatsapp_number'),
                'facebook_url' => Setting::get('facebook_url'),
                'twitter_url' => Setting::get('twitter_url'),
                'instagram_url' => Setting::get('instagram_url'),
                'youtube_url' => Setting::get('youtube_url'),
            ];
            $view->with('settings', $settings);
        });
    }
}
