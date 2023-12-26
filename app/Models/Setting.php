<?php

namespace App\Models;

use App\Models\Scopes\MyItems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'value', 'author_id'];

    /**
     * Executes when the class is booted.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MyItems);
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  Builder  $query The query builder instance.
     * @param  string  $type The type parameter.
     *
     * @throws \Some_Exception_Class The exception that may be thrown.
     */
    public function scopeOfKey(Builder $query, string $type): void
    {
        $query->where('title', $type);
    }
}
