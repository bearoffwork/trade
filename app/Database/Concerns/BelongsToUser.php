<?php

namespace App\Database\Concerns;

use App\Database\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid', 'id', __FUNCTION__);
    }
}
