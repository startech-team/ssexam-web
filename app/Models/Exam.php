<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam_acc;
use App\Models\Exam_ques;

class Exam extends Model
{

    use HasFactory;

    protected $fillable = [
        'exam_id', 'exam_nm', 'start_dt', 'end_dt', 'win_rate', 'duration', 'exam_type'
    ];

    protected $casts = ['exam_id' => 'string'];

    protected $primaryKey = "exam_id";
}
