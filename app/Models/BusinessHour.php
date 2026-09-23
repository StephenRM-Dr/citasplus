<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'day_of_week', 'open_time', 'close_time', 'is_closed'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
