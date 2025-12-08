<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'consultant_id', 'content'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function consultant() {
        return $this->belongsTo(User::class, 'consultant_id');
    }
}
