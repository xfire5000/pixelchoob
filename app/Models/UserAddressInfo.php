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

    public static array $TYPES = ['address' => ['title' => 'نشانی', 'value' => 'address'],
        'phone' => ['title' => 'شماره تلفن ثابت', 'value' => 'phone'], 'mobile' => ['title' => 'شماره همراه', 'value' => 'mobile']];

    public function scopeOfType(Builder $query, string|array $type): void
    {
        $query->whereIn('type', is_array($type) ? $type : [$type]);
    }
}
