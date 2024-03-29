<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $appends = ['avatar_path', 'is_active', 'last_active_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function getAvatarPathAttribute(): string
    {
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?background=d5d3f8&color=7269ef&name=$name";
    }

    public function getIsActiveAttribute(): bool
    {
        return cache()->has("is_active$this->id");
    }

    public function getLastActiveAtAttribute()
    {
        return Helpers::getLastActiveAt($this->id);
    }
}
