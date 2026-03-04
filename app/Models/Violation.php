<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Violation extends Model
{
    protected $fillable = [
        'trader_id',
        'officer_id',
        'violation_type',
        'officer_notes',
        'ai_raw_message',
        'final_letter',
        'status'
    ];

    public function trader()
    {
        return $this->belongsTo(User::class, 'trader_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}