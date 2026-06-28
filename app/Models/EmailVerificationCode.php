<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerificationCode extends Model
{
    protected $fillable = [
        'user_id',
        'code_hash',
        'expires_at',
        'attempts',
        'used_at',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'attempts'   => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /** Code has passed its expiry time */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /** Code has already been used */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /** Failed attempts have reached the limit */
    public function isLocked(): bool
    {
        return $this->attempts >= 5;
    }

    /** Returns remaining attempts before lock */
    public function remainingAttempts(): int
    {
        return max(0, 5 - $this->attempts);
    }

    /** Seconds remaining before expiry (0 if already expired) */
    public function secondsRemaining(): int
    {
        return max(0, (int) now()->diffInSeconds($this->expires_at, false));
    }

    // ── Static Factories ───────────────────────────────────────────────────

    /**
     * Generate a new 8-digit OTP code for a user.
     * Invalidates (deletes) all previous active codes for that user first.
     *
     * @return array{code: string, model: self}
     */
    public static function generateFor(int $userId, string $ip = null): array
    {
        // Invalidate all previous unused codes for this user
        static::where('user_id', $userId)->whereNull('used_at')->delete();

        // Generate cryptographically secure 8-digit code
        $code = str_pad(random_int(0, 99_999_999), 8, '0', STR_PAD_LEFT);

        $model = static::create([
            'user_id'    => $userId,
            'code_hash'  => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $ip,
        ]);

        return ['code' => $code, 'model' => $model];
    }

    /**
     * Fetch the latest active (unused, unexpired) code for a user.
     */
    public static function latestActiveFor(int $userId): ?self
    {
        return static::where('user_id', $userId)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Fetch the latest code (any state) for a user — used for resend cooldown.
     */
    public static function latestFor(int $userId): ?self
    {
        return static::where('user_id', $userId)->latest()->first();
    }

    /**
     * Mark this code as successfully used.
     */
    public function markUsed(): void
    {
        $this->update(['used_at' => now()]);
    }
}
