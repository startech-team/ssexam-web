<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_acc extends Model
{
    use HasFactory;

     protected $fillable = [
        'exam_id', 'acc_id', 'take_exam_dt', 'remaing_time', 'take_exam_end_flg'
    ];
}
