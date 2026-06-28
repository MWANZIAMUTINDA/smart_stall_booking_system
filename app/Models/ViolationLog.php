<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationLog extends Model
{
    protected $fillable = ['violation_id', 'user_id', 'action'];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
