<?php

namespace App\Models;

use App\Enums\MoneyAct;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
