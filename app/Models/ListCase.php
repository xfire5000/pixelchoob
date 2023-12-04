<?php

namespace App\Models;

use App\Models\Scopes\MyItems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;

class ListCase extends Model
{
    use HasFactory,HasPersianSlug,Searchable,SoftDeletes;

    protected $fillable = ['user_id', 'color', 'title', 'slug', 'description', 'pvc', 'stock', 'archived'];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MyItems);
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function listItems(): HasMany
    {
        return $this->hasMany(ListItem::class, 'list_case_id', 'id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function sharedWithUsers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'user_manager');
    }

    public function scopeArchived(Builder $query): void
    {
        $query->where('archived', true);
    }
}
