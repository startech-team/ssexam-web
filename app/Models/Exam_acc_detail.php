<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_acc_detail extends Model
{
    use HasFactory;

     protected $fillable = [
        'exam_id', 'acc_id', 'question_id','my_answer'
    ];
}
