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

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MyItems);
    }

    public function scopeOfKey(Builder $query, string $type): void
    {
        $query->where('title', $type);
    }
}
