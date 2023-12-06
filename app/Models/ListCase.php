<?php

namespace App\Models;

use App\Models\Scopes\MyItems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class ListCase extends Model
{
    use HasFactory,Searchable,SoftDeletes;

    protected $fillable = ['author_id', 'user_id', 'title', 'description', 'pvc', 'stock', 'archived',
        'viewed'];

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

    public function listItems(): HasMany
    {
        return $this->hasMany(ListItem::class, 'list_case_id', 'id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeArchived(Builder $query, bool $archived = true): void
    {
        $query->where('archived', $archived);
    }

    public function scopeViewed(Builder $query, bool $viewed = true): void
    {
        $query->where('viewed', $viewed);
    }

    public function scopeInbox(Builder $query): void
    {
        $query->withoutGlobalScopes()->whereHas('user', fn ($user) => $user->where('id', auth()->id()));
    }
}
