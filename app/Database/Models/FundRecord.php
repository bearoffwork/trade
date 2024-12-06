<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * 
 *
 * @property int $id
 * @property string|null $fundable_type
 * @property int|null $fundable_id
 * @property string $amount
 * @property string $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent|null $Fundable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundRecord query()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class FundRecord extends Model
{
    public function Fundable(): MorphTo
    {
        return $this->morphTo('fundable');
    }
}
