<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialClosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'closure_date', 'reason'
    ];

    protected $casts = [
        'closure_date' => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
