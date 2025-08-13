<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'letter_id',
        'from_user_id',
        'instruction',
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class, 'letter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(
            User::class,
            'disposition_recipients',
            'disposition_id',
            'to_user_id'
        );
    }

}
