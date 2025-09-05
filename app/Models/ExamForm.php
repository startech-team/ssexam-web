<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'body', 'option1', 'option2', 'option3', 'option4', 'my_answer', 'ord_no', 'pre_ord_no', 'nxt_ord_no', 'correct_answer'
    ];
}
