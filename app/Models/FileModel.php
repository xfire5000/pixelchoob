<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class FileModel extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['src', 'title', 'size', 'type', 'user_id'];

    public static array $TYPES = [
        'all' => 0,
        'image' => 1,
        'video' => 2,
        'other' => 3,
        'audio' => 4,
    ];

    /**
     * Retrieve the associated user for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Retrieves the array representation of the object for search indexing.
     *
     * @return array The searchable array representation of the object.
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'src' => $this->src,
        ];
    }
}
