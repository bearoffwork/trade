<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $id
 * @property string|null $type_desc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ItemTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemType query()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class ItemType extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;
}
