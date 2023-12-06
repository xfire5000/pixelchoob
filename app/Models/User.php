<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Interfaces\User as InterfacesUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements InterfacesUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles,SoftDeletes;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function contacts(): MorphToMany
    {
        return $this->morphToMany(User::class, 'user_manager')->with(['addressInfos']);
    }

    public function addressInfos(): HasMany
    {
        return $this->hasMany(UserAddressInfo::class, 'user_id', 'id');
    }

    public function getPermissionArray(): mixed
    {
        return $this->getAllPermissions()->mapWithKeys(fn ($pr) => [$pr['name'] => true]
        );
    }

    public function getRole(): Collection
    {
        return $this->getRoleNames();
    }

    public static function findOrCreate(string $email, array $array): InterfacesUser
    {
        $user = static::where('email', 'like', "%$email%")->first();

        if (! $user) {
            return static::query()->create($array);
        }

        return $user;
    }
}
