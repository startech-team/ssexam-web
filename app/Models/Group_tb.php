<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_tb extends Model
{
    use HasFactory;

     protected $fillable = [
        'group_id', 'group_name', 'order', 'group_icon'
    ];
}
