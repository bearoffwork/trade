<?php

namespace App\Database\Models;

use App\Database\Concerns\BelongsToItem;
use App\Database\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $uid
 * @property int|null $iid
 * @property int|null $fid
 * @property numeric $amount
 * @property numeric $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Database\Models\User|null $Item
 * @property-read \App\Database\Models\User $User
 * @property-read mixed $username
 * @method static Builder<static>|WalletRecord fund()
 * @method static Builder<static>|WalletRecord newModelQuery()
 * @method static Builder<static>|WalletRecord newQuery()
 * @method static Builder<static>|WalletRecord query()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class WalletRecord extends Model
{
    use BelongsToItem;
    use BelongsToUser;

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('user', fn(Builder $builder) => $builder->whereNotNull('uid'));
    }

    public function scopeFund(Builder $query): Builder
    {
        return $query->withoutGlobalScope('user')
            ->whereNull('uid');
    }

    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->User->getAttribute(User::getFrontendDisplayColumn()) ?? null,
        );
    }
}
