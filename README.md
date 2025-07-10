A complete astrologer website in PHP Laravel that integrates with the Prokerala Astrology API.

## Key Features:

### Multiple Astrology Calculators:

- Birth Chart Analysis
- Kundli (Vedic Birth Chart) Generator
- Daily Panchang
- Compatibility/Kundli Matching Calculator


### Daily Horoscope Section:

- Detailed daily predictions
- Current planetary transits (western)


### Blog System:

 Full CRUD functionality
SEO-friendly URLs
Related articles feature


### Contact Form:

- Email notifications
- Form validation
- Database storage


### Modern UI/UX:

- Responsive Bootstrap 5 design
- Gradient effects and animations
- Professional color scheme
- Mobile-friendly interface


### Technical Features:

- Secure API integration with Prokerala
- Caching for performance

## Setup Instructions:

#### Get Prokerala API Credentials:

- Sign up for a [Prokerala API](https://api.prokerala.com) account
- Get your Client ID and Client Secret


#### Configure Environment:

- Add your Prokerala credentials to .env
- Set up your database connection
- Configure mail settings


### Install & Run:

```
git clone https://github.com/prokerala/laravel-astrologer-website
cd laravel-astrologer-website
```

Copy `.env.example` to `.env` manually or using the appropriate command below.

```
# Linux/Unix
cp .env.example .env
# Windows
copy .env.example .env
```

Setup and initialize dependencies.
```
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Start the development server

```
composer run dev
```

Open [http://localhost:8000](http://localhost:8000) to open the application. Visit [http://localhost:8000/admin](http://localhost:8000/admin) to manage blog posts and contact messages.

## License

The source code is licensed under the [MIT license](https://opensource.org/licenses/MIT).
