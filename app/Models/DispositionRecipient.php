<?php

namespace App\Models;

use App\Enums\DispositionStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status_label',
    ];

    /**
     * Dapatkan label yang mudah dibaca untuk peran pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status?->getLabel(),
        );
    }

}
