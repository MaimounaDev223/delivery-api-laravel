<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'sender_name',
        'recipient_name',
        'recipient_phone',
        'destination_address',
        'status',
    ];
}