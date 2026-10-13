<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'ticket_number',
        'patient_type',
        'current_step',
        'status',
        'counter_number',
        'systolic_bp',
        'diastolic_bp',
        'temperature',
    ];
}
