<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AstrologerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HoroscopeController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Astrologer Profile
Route::get('/astrologer', [AstrologerController::class, 'profile'])->name('astrologer.profile');

// Horoscope Routes
Route::prefix('horoscope')->group(function () {
    Route::get('/daily', [HoroscopeController::class, 'daily'])->name('horoscope.daily');
    Route::get('/daily/{sign}', [HoroscopeController::class, 'show'])->name('horoscope.show');
});

// Calculator Routes
Route::prefix('calculators')->group(function () {
    Route::get('/birth-chart', [CalculatorController::class, 'birthChart'])->name('calculator.birth-chart');
    Route::post('/birth-chart', [CalculatorController::class, 'calculateBirthChart'])->name('calculator.birth-chart.calculate');

    Route::get('/kundli', [CalculatorController::class, 'kundli'])->name('calculator.kundli');
    Route::post('/kundli', [CalculatorController::class, 'calculateKundli'])->name('calculator.kundli.calculate');

    Route::get('/panchang', [CalculatorController::class, 'panchang'])->name('calculator.panchang');
    Route::post('/panchang', [CalculatorController::class, 'calculatePanchang'])->name('calculator.panchang.calculate');

    Route::get('/compatibility', [CalculatorController::class, 'compatibility'])->name('calculator.compatibility');
    Route::post('/compatibility', [CalculatorController::class, 'calculateCompatibility'])->name('calculator.compatibility.calculate');
});

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Dashboard Route - redirects based on user role
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    if (auth()->user()->is_admin) {
        return redirect('/admin');
    }
    return redirect()->route('account.dashboard');
})->name('dashboard');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('index');

    // Blog Management
    Route::resource('blogs', AdminBlogController::class);

    // User Management (placeholder routes for future implementation)
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    // Contact Management
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.update-status');
    Route::post('/contacts/{contact}/respond', [AdminContactController::class, 'respond'])->name('contacts.respond');
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/bulk-action', [AdminContactController::class, 'bulkAction'])->name('contacts.bulk-action');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/validate-client', [SettingsController::class, 'validateClientId'])->name('settings.validate-client');

    // Analytics (placeholder routes for future implementation)
    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');
});

// User Dashboard/Profile Management Routes
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', function () {
        return view('account.dashboard');
    })->name('dashboard');

});

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
