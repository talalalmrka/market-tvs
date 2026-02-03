<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,
        Notifiable,
        TwoFactorAuthenticatable,
        HasRoles,
        InteractsWithMedia,
        HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $appends = [
        'avatar'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = self::generateSlug($user->name);
            }
        });
        static::updating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = self::generateSlug($user->name);
            }
        });
    }
    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/assets/images/user.svg')
            ->useFallbackPath(public_path('/assets/images/user.svg'));
    }
    public function avatar(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMediaUrl('avatar'));
    }
}
