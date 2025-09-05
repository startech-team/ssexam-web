<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'acc_id', 'exam_nm', 'start_dt', 'end_dt', 'take_exam_dt', 'result', 'mark', 'status', 'question_count', 'win_mark', 'take_exam_status', 'name', 'group_id', 'group_name'
    ];
}
