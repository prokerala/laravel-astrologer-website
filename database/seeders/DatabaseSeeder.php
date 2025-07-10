<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);


        $blogs = [
            [
                'title' => 'Understanding Your Birth Chart: A Beginner\'s Guide',
                'excerpt' => 'Learn the basics of reading and interpreting your astrological birth chart.',
                'content' => '<p>Your birth chart is a snapshot of the sky at the exact moment you were born...</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'The Importance of Moon Signs in Vedic Astrology',
                'excerpt' => 'Discover why your Moon sign is considered more important than your Sun sign in Vedic astrology.',
                'content' => '<p>In Vedic astrology, the Moon sign holds special significance...</p>',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            // Add more blog posts here
        ];

        foreach ($blogs as $blog) {
            $blog['slug'] = Str::slug($blog['title']);
            $blog['meta_title'] = $blog['title'];
            $blog['meta_description'] = $blog['excerpt'];
            Blog::create($blog);
        }
    }
}
