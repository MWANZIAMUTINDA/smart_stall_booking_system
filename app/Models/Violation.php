<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    //
    protected $fillable = [
    'trader_id',
    'officer_id',
    'violation_type',
    'officer_notes',
    'ai_raw_message',
    'final_letter',
    'status'
];
}
