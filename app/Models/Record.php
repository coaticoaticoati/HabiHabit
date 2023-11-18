<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'habit_id', 'achieved_at'
    ];

    public function habits() {
        
        return $this->belongsTo(Habit::class);
    
    }
}
