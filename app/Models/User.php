<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_image'
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
     * The storage format of the model's date columns.
     * U = Unix Timestamp
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
/**
     * Define a one-to-one relationship with the user's profile image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profileImage(): HasOne
    {
        return $this->hasOne(ProfileImage::class);
    }


    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    // ...

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }

    }