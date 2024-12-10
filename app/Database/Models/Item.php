<?php

namespace App\Database\Models;

use App\Enums\ItemRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 *
 * @property int $id
 * @property string $item_name
 * @property string $item_type
 * @property int $qty
 * @property string $act_id
 * @property string $tax_rate
 * @property string $fund_rate
 * @property int|null $total_amt
 * @property int|null $posted_amt
 * @property int|null $buyer_uid
 * @property string $drop_at
 * @property string|null $close_at
 * @property string|null $pay_at
 * @property int $create_uid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Database\Models\Activity $Activity
 * @property-read \App\Database\Models\User|null $Buyer
 * @property-read \App\Database\Models\ItemType $ItemType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Database\Models\User> $Operators
 * @property-read int|null $operators_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Database\Models\User> $Users
 * @property-read int|null $users_count
 * @method static \Database\Factories\Database\Models\ItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Item extends Model
{
    use HasFactory;

    public function Users(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                related: User::class,
                table: 'item_users',
                foreignPivotKey: 'iid',
                relatedPivotKey: 'uid',
                parentKey: 'id',
                relatedKey: 'id',
                relation: __FUNCTION__,
            )
            ->withPivotValue([
                'item_role' => ItemRole::Participant,
            ]);
    }

    public function Operators(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                related: User::class,
                table: 'item_users',
                foreignPivotKey: 'iid',
                relatedPivotKey: 'uid',
                parentKey: 'id',
                relatedKey: 'id',
                relation: __FUNCTION__,
            )
            ->withPivotValue([
                'item_role' => ItemRole::Operator,
            ]);
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
