<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $table = 'term';

    protected $fillable = [
        'term_id', 'category_id', 'word','explanation'
    ];
}
