<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddressInfo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id', 'type', 'description', 'isShow'];

    public static array $TYPES = ['address' => ['title' => __('validation.attributes.address'), 'value' => 'address'],
        'phone' => ['title' => __('validation.attributes.phone'), 'value' => 'phone'], 'mobile' => ['title' => __('validation.attributes.mobile'), 'value' => 'mobile']];

    public function scopeOfType(Builder $query, string|array $type): void
    {
        $query->whereIn('type', is_array($type) ? $type : [$type]);
    }
}
