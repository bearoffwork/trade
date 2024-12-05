<?php

namespace App\Models;

use App\Enums\MoneyAct;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property MoneyAct                        $act
 * @property int|null                        $iid
 * @property int                             $uid
 * @property numeric                         $amount
 * @property numeric                         $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $User
 * @property-read mixed $username
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMoney newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMoney newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMoney query()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class UserMoney extends Model
{
    protected function casts(): array
    {
        return [
            'act' => MoneyAct::class,
            'balance' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid', 'id', __FUNCTION__);
    }

    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->User->getAttribute(User::getFrontendDisplayColumn()) ?? null,
        );
    }
}
