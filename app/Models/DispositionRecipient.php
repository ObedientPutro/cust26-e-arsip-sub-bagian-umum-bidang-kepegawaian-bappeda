<?php

namespace App\Models;

use App\Enums\DispositionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispositionRecipient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'disposition_id',
        'to_user_id',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => DispositionStatusEnum::class
        ];
    }

    public function disposition()
    {
        return $this->belongsTo(Disposition::class, 'disposition_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

}
