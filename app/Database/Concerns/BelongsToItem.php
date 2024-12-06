<?php

namespace App\Database\Concerns;

use App\Database\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToItem
{
    public function Item(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iid', 'id', __FUNCTION__);
    }
}
