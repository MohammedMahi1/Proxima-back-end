<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['notifiable_type', 'notifiable_id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
