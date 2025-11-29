<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MHQuestions extends Model
{
    protected $table = 'MHQuestions';
    protected $fillable = ['prompt','type','is_active'];
}