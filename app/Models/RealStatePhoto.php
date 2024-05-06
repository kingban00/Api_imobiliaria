<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealStatePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo', 'is_thumb'
    ];

    public function RealState(){
        return $this->belongsTo( RealState::class)->onDelete('cascade');
    }
}
