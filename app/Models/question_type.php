<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question_type extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type_id', 'question_type_nm'
    ];

    protected $casts = ['question_type_id' => 'string'];

    protected $primaryKey = "question_type_id";
}
