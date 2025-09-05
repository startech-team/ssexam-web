<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'exam_result_id','exam_id', 'acc_id', 'status','resultmark',
         'take_exam_status', 'win_mark', 'question_count','mark'
    ];
}
