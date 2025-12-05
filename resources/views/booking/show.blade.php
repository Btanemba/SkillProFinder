<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Service - {{ config('app.name', 'SkillConnect') }}</title>
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
        .container { max-width: 800px; margin: 0 auto; }
        .back-link {
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            transition: all 0.3s;
        }
        .back-link:hover { background: rgba(255, 255, 255, 0.3); }
        .booking-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        h1 { color: #333; font-size: 32px; margin-bottom: 30px; }
        .service-info {
            display: flex;
            gap: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .service-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
        .service-details h2 { color: #333; font-size: 20px; margin-bottom: 5px; }
        .service-details p { color: #666; font-size: 14px; }
        .service-price {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-top: 10px;
        }
        .form-group { margin-bottom: 25px; }
        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .error { color: #dc3545; font-size: 13px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="javascript:history.back()" class="back-link">← Back</a>
        
        <div class="booking-card">
            <h1>Book Service</h1>
            
            <div class="service-info">
                @if($service->person->profile_picture)
                    <img src="{{ asset('storage/' . $service->person->profile_picture) }}" alt="{{ $service->person->user->first_name }}" class="service-image">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($service->person->user->first_name) }}&size=80&background=667eea&color=fff" alt="{{ $service->person->user->first_name }}" class="service-image">
                @endif
                
                <div class="service-details">
                    <h2>{{ $service->person->user->first_name }} {{ $service->person->user->last_name }}</h2>
                    <p>{{ $service->name }}@if($service->skill_type) - {{ $service->skill_type }}@endif</p>
                    <p>{{ $service->person->city }}, {{ $service->person->country }}</p>
                    <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Service Price: <strong style="color: #333;">£{{ number_format($service->price, 2) }}</strong></p>
                        <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Platform Fee: <strong style="color: #333;">£5.00</strong></p>
                        <div style="border-top: 2px solid #667eea; margin: 10px 0; padding-top: 10px;">
                            <p style="color: #333; font-size: 16px; font-weight: 700;">Total: <span style="color: #667eea; font-size: 24px;">£{{ number_format($service->price + 5, 2) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p class="error">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('client.booking.store') }}">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                
                <div class="form-group">
                    <label for="booking_date">Preferred Date *</label>
                    <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="booking_time">Preferred Time *</label>
                    <input type="time" id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message (Optional)</label>
                    <textarea id="message" name="message" placeholder="Add any special requirements or questions...">{{ old('message') }}</textarea>
                </div>
                
                <button type="submit" class="btn-submit">Confirm Booking</button>
            </form>
        </div>
    </div>
</body>
</html>
