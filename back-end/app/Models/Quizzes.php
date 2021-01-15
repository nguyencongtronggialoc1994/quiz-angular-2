<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
    use HasFactory;
    protected $table='quizzes';
    protected $fillable=['text','option1','option2','option3','option4','correctAnswer','category_id'];
}
