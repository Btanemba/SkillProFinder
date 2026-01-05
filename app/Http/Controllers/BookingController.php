<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function show($serviceId)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('register', ['type' => 'client', 'redirect' => route('client.booking.show', $serviceId)])
                ->with('message', 'Please register as a client to book this service.');
        }
        
        $service = Service::with(['person.user'])->findOrFail($serviceId);
        return view('booking.show', compact('service'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'message' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        
        $platformFee = 5.00; // £5 platform fee
        $totalPrice = $service->price + $platformFee;

        $booking = Booking::create([
            'client_id' => Auth::id(),
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'message' => $validated['message'],
            'service_price' => $service->price,
            'platform_fee' => $platformFee,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('client.booking.confirmation', $booking->id)
            ->with('success', 'Booking request submitted successfully!');
    }
    


    public function confirmation($bookingId)
    {
        $booking = Booking::with(['service.person.user'])->findOrFail($bookingId);
        
        // Debug logging for troubleshooting
        \Log::info('Booking confirmation debug', [
            'booking_id' => $bookingId,
            'booking_client_id' => $booking->client_id,
            'auth_id' => \Auth::id(),
            'user' => \Auth::user(),
        ]);

        // Make sure the booking belongs to the authenticated user
        // if ($booking->client_id !== Auth::id()) {
        //     abort(403);
        // }
        
        if ((int) $booking->client_id !== (int) Auth::id()) {
    abort(403);
}

        return view('booking.confirmation', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Booking::with(['service.person.user'])
            ->where('client_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.my-bookings', compact('bookings'));
    }

    public function providerBookings()
    {
        // Get bookings for services owned by the logged-in provider
        $bookings = Booking::with(['client', 'service'])
            ->whereHas('service.person', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', 'confirmed') // Only show confirmed bookings
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('booking.provider-bookings', compact('bookings'));
    }
}
