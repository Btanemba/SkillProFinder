<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmed - {{ config('app.name', 'SkillConnect') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { max-width: 600px; width: 100%; }
        .confirmation-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .success-icon svg {
            width: 50px;
            height: 50px;
            stroke: white;
            stroke-width: 3;
        }
        h1 { color: #333; font-size: 32px; margin-bottom: 15px; }
        .subtitle { color: #666; font-size: 16px; margin-bottom: 30px; }
        .booking-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            text-align: left;
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #666; font-weight: 500; }
        .detail-value { color: #333; font-weight: 600; }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            background: #fef3c7;
            color: #f59e0b;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #333;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-card">
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1>Booking Confirmed!</h1>
            <p class="subtitle">Your booking request has been submitted successfully</p>
            
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#{{ $booking->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Service Provider</span>
                    <span class="detail-value">{{ $booking->service->person->user->first_name }} {{ $booking->service->person->user->last_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Service</span>
                    <span class="detail-value">{{ $booking->service->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ $booking->booking_date->format('F d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time</span>
                    <span class="detail-value">{{ date('g:i A', strtotime($booking->booking_time)) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Service Price</span>
                    <span class="detail-value">£{{ number_format($booking->service_price, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Platform Fee</span>
                    <span class="detail-value">£{{ number_format($booking->platform_fee, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Price</span>
                    <span class="detail-value">£{{ number_format($booking->total_price, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="status-badge">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>
            
            <p style="color: #666; font-size: 14px; line-height: 1.6;">
                The service provider will review your booking and confirm it shortly. You will be notified once they accept your request.
            </p>
            
            <div class="btn-group">
                <a href="{{ route('booking.my-bookings') }}" class="btn btn-primary">View My Bookings</a>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
