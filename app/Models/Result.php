<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes; // Tambahkan ini jika ingin soft delete

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}