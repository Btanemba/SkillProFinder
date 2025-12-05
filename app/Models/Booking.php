<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'booking_date',
        'booking_time',
        'message',
        'status',
        'service_price',
        'platform_fee',
        'total_price',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
