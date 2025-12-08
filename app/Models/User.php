<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',          // <-- add this if you added a role column
        'consultant_id', // <-- add this if you added consultant_id column
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Quotes saved by the user.
     */
    public function savedQuotes()
    {
        return $this->belongsToMany(Quote::class, 'quote_user_saves')->withTimestamps();
    }

    /**
     * The consultant assigned to this user.
     */
    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    /**
     * All clients assigned to this consultant.
     */
    public function clients()
    {
        return $this->hasMany(User::class, 'consultant_id');
    }

    // Redirect after login based on role
    public function authenticated(Request $request, $user)
    {
        if ($user->role === 'consultant') {
            return redirect()->route('consultant.dashboard');
        }
        return redirect()->route('dashboard'); // regular user dashboard
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'consultant_id');
    }
}
