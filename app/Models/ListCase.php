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
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;

class ListCase extends Model
{
    use HasFactory,HasPersianSlug,Searchable,SoftDeletes;

    protected $fillable = ['author_id', 'user_id', 'title', 'description', 'pvc', 'stock', 'archived',
        'viewed', 'slug'];

    /**
     * A description of the entire PHP function.
     *
     * @return Some_Return_Value
     *
     * @throws Some_Exception_Class description of exception
     */
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MyItems);
    }

    /**
     * Retrieves the SlugOptions for generating and saving slugs.
     *
     * @return SlugOptions The SlugOptions object.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['user_id', 'title'])
            ->saveSlugsTo('slug');
    }

    /**
     * Converts the object into an array that can be used for searching.
     *
     * @return array The searchable array.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Converts the object into an array that can be used for searching.
     *
     * @return array The searchable array.
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
        ];
    }

    /**
     * Retrieves a collection of ListItem models related to this ListCase model.
     */
    public function listItems(): HasMany
    {
        return $this->hasMany(ListItem::class, 'list_case_id', 'id');
    }

    /**
     * Returns the author associated with this model.
     */
    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id')->with('addressInfos');
    }

    /**
     * A description of the entire PHP function.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Retrieves the invoice associated with the current model.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'list_case_id', 'id');
    }

    /**
     * Retrieves the ticket associated with this model.
     */
    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'list_case_id', 'id')->withCount('newMessages');
    }

    /**
     * Set the "archived" flag on the query.
     *
     * @param  Builder  $query The query builder instance.
     * @param  bool  $archived The value to set for the "archived" flag. Defaults to true.
     */
    public function scopeArchived(Builder $query, bool $archived = true): void
    {
        $query->where('archived', $archived);
    }

    /**
     * Sets the "viewed" scope on the query.
     *
     * @param  Builder  $query The query builder object.
     * @param  bool  $viewed Whether to include viewed or unviewed records. Default is true.
     */
    public function scopeViewed(Builder $query, bool $viewed = true): void
    {
        $query->withoutGlobalScopes()->where('viewed', $viewed);
    }

    /**
     * A description of the entire PHP function.
     *
     * @param  Builder  $query The query builder instance.
     *
     * @throws void
     */
    public function scopeInbox(Builder $query): void
    {
        $query->withoutGlobalScopes()->whereHas('user', fn ($user) => $user->where('id', auth()->id()));
    }

    /**
     * Generate the function comment for the given function body.
     *
     * @param  Builder  $query The query builder instance.
     * @param  bool  $has Determines whether to include records that have an invoice or not. Default is true.
     */
    public function scopeHasInvoice(Builder $query, bool $has = true): void
    {
        $has ? $query->whereHas('invoice') : $query->whereDoesntHave('invoice');
    }
}
