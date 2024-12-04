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
            foreignPivotKey: 'iid',
            relatedPivotKey: 'uid',
            parentKey: 'id',
            relatedKey: 'id',
            relation: __FUNCTION__,
        );
    }

    public function ItemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type', 'id', __FUNCTION__);
    }

    public function Buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_uid', 'id', __FUNCTION__);
    }

    public function Activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'act_id', 'id', __FUNCTION__);
    }
}
