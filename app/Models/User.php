<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'username',
        'password',
        'role',
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
            'role' => UserRoleEnum::class
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'role_label',
    ];

    /**
     * Dapatkan label yang mudah dibaca untuk peran pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function roleLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->role?->getLabel(),
        );
    }

    public function letters()
    {
        return $this->hasMany(Letter::class, 'user_id');
    }

    public function dispositions()
    {
        return $this->hasMany(Disposition::class, 'from_user_id');
    }

    public function receivedDispositions()
    {
        return $this->belongsToMany(
            Disposition::class,
            'disposition_recipients',
            'to_user_id',
            'disposition_id'
        )->withPivot('status');
    }

}
