<?php

namespace App\Database\Concerns;

use App\Database\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToItem
{
    public function Item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'iid', 'id', __FUNCTION__);
    }
}
