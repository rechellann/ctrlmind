<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MHResponses extends Model
{
    protected $table = 'MHResponses'; // matches your table name
    protected $fillable = ['user_id','question_id','response_text','response_number'];
}
