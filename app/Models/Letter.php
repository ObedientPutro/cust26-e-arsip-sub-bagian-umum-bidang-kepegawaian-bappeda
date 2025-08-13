<?php

namespace App\Models;

use App\Enums\LetterTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'letter_number',
        'subject',
        'sender',
        'recipient',
        'letter_date',
        'type',
        'file_path',
        'is_disposed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LetterTypeEnum::class
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function dispositions()
    {
        return $this->hasMany(Disposition::class, 'letter_id');
    }

}
