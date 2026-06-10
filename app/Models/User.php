<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use App\Concerns\DefaultEntity;
use App\Concerns\OptionTrait;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/**
 * @mixin IdeHelperUser
 */
#[Fillable(['name', 'email', 'password', 'role', 'phone', 'trial_start_date', 'plan', 'plan_start_date', 'plan_end_date'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use DefaultEntity, Filterable, HasApiTokens, HasFactory, Notifiable, OptionTrait, Sortable, TwoFactorAuthenticatable;

    protected $table = 'users';

    protected $keyType = 'int';

    protected $primaryKey = 'id';

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
            'trial_start_date' => 'datetime',
            'plan_start_date' => 'datetime',
            'plan_end_date' => 'datetime',
        ];
    }

    /**
     * Columns available for filtering.
     */
public static $filterColumns = [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'role' => 'Role',
    ];

    public static $sortColumns = [
        'name',
        'email',
        'phone',
        'role',
    ];

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'role' => 'string',
            'password' => 'string',
        ];
    }

    public static function field_name()
    {
        return 'users_nama';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function sendPushNotification(string $title, string $body, string $url = '/', ?string $icon = null): void
    {
        $subscriptions = $this->pushSubscriptions;

        if ($subscriptions->isEmpty()) {
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => config('push.vapid.subject'),
                'publicKey' => config('push.vapid.public_key'),
                'privateKey' => config('push.vapid.private_key'),
            ],
        ];

        $webPush = new WebPush($auth);
        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'url' => $url,
            'icon' => $icon ?? url('/apple-touch-icon.png'),
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
                'contentEncoding' => $sub->content_encoding ?? 'aes128gcm',
            ]);
            $webPush->queueNotification($subscription, $payload);
        }

        foreach ($webPush->flush() as $report) {
            if (! $report->isSuccess() && $report->isSubscriptionExpired()) {
                PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
            }
        }
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
}
