<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model // Modelクラスを継承してHabitクラスを作成
{
    use HasFactory;

    // カラムにデータ挿入を許可する
    protected $fillable = [
        'name', 'user_id', 'archive', 'goal'
    ];

    public function user() {
        
        return $this->belongsTo(User::class);
    
    }

    public function records() {

        return $this->hasMany(Record::class);
    
    }

    public function memos() {

        return $this->hasMany(Memo::class);
    
    }

}
