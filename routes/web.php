<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    // Get unique skills for the dropdown
    $skills = Service::distinct()->pluck('name')->sort()->values();
    return view('welcome', compact('skills'));
});

Route::get('/search-services', function () {
    $query = Service::with(['person.user']);
    
    // Filter by skill
    if (request('skill')) {
        $query->where('name', request('skill'));
    }
    
    // Filter by skill type
    if (request('skill_type')) {
        $query->where('skill_type', request('skill_type'));
    }
    
    // Filter by city
    if (request('city')) {
        $query->whereHas('person', function($q) {
            $q->where('city', request('city'));
        });
    }
    
    // Filter by country
    if (request('country')) {
        $query->whereHas('person', function($q) {
            $q->where('country', request('country'));
        });
    }
    
    $services = $query->get();
    
    return view('search-results', compact('services'));
})->name('search.services');

// Public route for loading cities
Route::get('/api/cities/{countryCode}', function ($countryCode) {
    if ($countryCode === 'GB') {
        $jsonPath = storage_path('app/uk-cities.json');
        if (file_exists($jsonPath)) {
            $cities = json_decode(file_get_contents($jsonPath), true);
            $cityOptions = [];
            foreach ($cities as $city) {
                $cityOptions[$city] = $city;
            }
            return response()->json($cityOptions);
        }
    }
    return response()->json([]);
})->name('api.cities');

// Booking routes (requires authentication and email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/book-service/{service}', [BookingController::class, 'show'])->name('client.booking.show');
    Route::post('/book-service', [BookingController::class, 'store'])->name('client.booking.store');
   
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my-bookings');
    Route::get('/provider-bookings', [BookingController::class, 'providerBookings'])->name('booking.provider-bookings');
});
 Route::get('/booking-confirmation/{booking}', [BookingController::class, 'confirmation'])->name('client.booking.confirmation');
 



