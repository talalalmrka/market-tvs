<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasMeta;
use App\Traits\HasSlug;
use App\Traits\WithPermalink;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,
        HasMeta,
        HasRoles,
        HasSlug,
        InteractsWithMedia,
        Notifiable,
        TwoFactorAuthenticatable,
        WithPermalink;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'slug',
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
        'avatar',
        'screens_permalink',
        'email_verification_url',
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
            ->map(fn ($word) => Str::substr($word, 0, 1))
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

    public function displayName(): Attribute
    {
        return Attribute::get(fn () => $this->getMeta('display_name', $this->name));
    }

    public function avatar(): Attribute
    {
        return Attribute::get(fn () => $this->getFirstMediaUrl('avatar'));
    }

    public function screensPermalink(): Attribute
    {
        return Attribute::get(fn () => ! empty($this->id) ? route('screens', $this) : '');
    }

    /**
     * Resolve a setting from various input types
     *
     * @param  int|string|User  $user  Can be a User instance, ID, or name, email string
     * @return User|null Returns the User instance or null if not found
     */
    public static function resolve(int|string|User $user)
    {
        if ($user instanceof User) {
            return $user;
        }
        if (is_int($user) || is_numeric($user)) {
            return static::find($user);
        }

        if (is_string($user)) {
            return self::where('name', $user)->orWhere('email', $user)->first();
        }

        return null;
    }

    public function emailVerificationUrl(): Attribute
    {
        return Attribute::get(function () {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $this->getKey(),
                    'hash' => sha1($this->getEmailForVerification()),
                ]
            );
        });
    }
}
