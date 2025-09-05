<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'question_type', 'title', 'body', 'option1', 'option2', 'option3', 'option4', 'correct_answer', 'question_type_nm', 'correct_answer1', 'correct_answer2', 'correct_answer3', 'correct_answer4', 'chk_flg'
    ];
}
