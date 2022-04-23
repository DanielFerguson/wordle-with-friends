<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date',
        'word_id'
    ];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
