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
        .container { max-width: 1200px; margin: 0 auto; }
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
        .info-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            color: #0369a1;
            border-left: 4px solid #0ea5e9;
        }
        .info-box strong { color: #0c4a6e; }
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
        .bookings-grid { display: grid; gap: 20px; }
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
        .booking-date {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        .booking-time {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .booking-price {
            font-size: 20px;
            font-weight: 700;
            color: #10b981;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #d1fae5;
            color: #059669;
        }
        @media (max-width: 768px) {
            .booking-card { grid-template-columns: 1fr; text-align: center; }
            .booking-meta { text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>My Confirmed Bookings</h1>
            <a href="/admin/dashboard" class="back-link">← Back to Dashboard</a>
        </header>
        
        <div class="info-box">
            <strong>ℹ️ Note:</strong> Only confirmed bookings are shown here. Pending bookings need admin approval before appearing in your list.
        </div>
        
        @if($bookings->isEmpty())
            <div class="no-bookings">
                <h2>No Confirmed Bookings Yet</h2>
                <p>You don't have any confirmed bookings at the moment. Once an admin confirms a booking for your services, it will appear here.</p>
            </div>
        @else
            <div class="bookings-grid">
                @foreach($bookings as $booking)
                    <div class="booking-card">
                        @if($booking->client->person && $booking->client->person->profile_picture)
                            <img src="{{ asset('storage/' . $booking->client->person->profile_picture) }}" alt="{{ $booking->client->first_name }}" class="booking-image">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->client->first_name) }}&size=80&background=667eea&color=fff" alt="{{ $booking->client->first_name }}" class="booking-image">
                        @endif
                        
                        <div class="booking-info">
                            <h3>{{ $booking->service->name }} - {{ $booking->client->first_name }} {{ $booking->client->last_name }}</h3>
                            <p><strong>Client Email:</strong> {{ $booking->client->email }}</p>
                            @if($booking->client->person && $booking->client->person->phone)
                                <p><strong>Phone:</strong> {{ $booking->client->person->phone }}</p>
                            @endif
                            @if($booking->message)
                                <p><strong>Message:</strong> {{ Str::limit($booking->message, 100) }}</p>
                            @endif
                            <p style="margin-top: 10px;"><span class="status-badge">{{ ucfirst($booking->status) }}</span></p>
                        </div>
                        
                        <div class="booking-meta">
                            <div class="booking-date">{{ $booking->booking_date->format('M d, Y') }}</div>
                            <div class="booking-time">{{ date('g:i A', strtotime($booking->booking_time)) }}</div>
                            <div class="booking-price">£{{ number_format($booking->service_price, 2) }}</div>
                            <p style="font-size: 12px; color: #999; margin-top: 5px;">Your earnings</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
