<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'type', 'send_before', 'message_template', 'active'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
