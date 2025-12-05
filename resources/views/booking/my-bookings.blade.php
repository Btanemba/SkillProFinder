<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings - {{ config('app.name', 'SkillConnect') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 { color: white; font-size: 32px; }
        .back-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            transition: all 0.3s;
        }
        .back-link:hover { background: rgba(255, 255, 255, 0.3); }
        .no-bookings {
            background: white;
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
        }
        .no-bookings h2 { color: #333; font-size: 24px; margin-bottom: 15px; }
        .no-bookings p { color: #666; margin-bottom: 25px; }
        .btn { 
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }
        .bookings-list { display: flex; flex-direction: column; gap: 20px; }
        .booking-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 20px;
            align-items: center;
        }
        .booking-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
        .booking-info h3 { color: #333; font-size: 18px; margin-bottom: 8px; }
        .booking-info p { color: #666; font-size: 14px; margin-bottom: 4px; }
        .booking-meta {
            text-align: right;
        }
        .booking-price {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #f59e0b; }
        .status-confirmed { background: #d1fae5; color: #059669; }
        .status-completed { background: #e0e7ff; color: #4f46e5; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        @media (max-width: 768px) {
            .booking-card { grid-template-columns: 1fr; text-align: center; }
            .booking-meta { text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>My Bookings</h1>
            <a href="/" class="back-link">← Back to Home</a>
        </header>
        
        @if($bookings->isEmpty())
            <div class="no-bookings">
                <h2>No Bookings Yet</h2>
                <p>You haven't made any bookings yet. Browse services and book your first appointment!</p>
                <a href="/" class="btn">Explore Services</a>
            </div>
        @else
            <div class="bookings-list">
                @foreach($bookings as $booking)
                    <div class="booking-card">
                        @if($booking->service->person->profile_picture)
                            <img src="{{ asset('storage/' . $booking->service->person->profile_picture) }}" alt="{{ $booking->service->person->user->first_name }}" class="booking-image">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->service->person->user->first_name) }}&size=80&background=667eea&color=fff" alt="{{ $booking->service->person->user->first_name }}" class="booking-image">
                        @endif
                        
                        <div class="booking-info">
                            <h3>{{ $booking->service->name }} with {{ $booking->service->person->user->first_name }} {{ $booking->service->person->user->last_name }}</h3>
                            <p><strong>Date:</strong> {{ $booking->booking_date->format('F d, Y') }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</p>
                            <p><strong>Location:</strong> {{ $booking->service->person->city }}, {{ $booking->service->person->country }}</p>
                            <p><strong>Booked on:</strong> {{ $booking->created_at->format('M d, Y') }}</p>
                            @if($booking->message)
                                <p><strong>Message:</strong> {{ Str::limit($booking->message, 100) }}</p>
                            @endif
                        </div>
                        
                        <div class="booking-meta">
                            <div class="booking-price">£{{ number_format($booking->total_price, 2) }}</div>
                            <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
