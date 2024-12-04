<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    public function Users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'item_users',
            foreignPivotKey: 'uid',
            relatedPivotKey: 'iid',
            parentKey: 'id',
            relatedKey: 'id',
            relation: __FUNCTION__,
        );
    }

    public function ItemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type', 'id', __FUNCTION__);
    }
}
