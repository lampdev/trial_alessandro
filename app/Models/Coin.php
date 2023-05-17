<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $id
 * @property string $external_id
 * @property string $symbol
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Coin extends Model
{
    use HasUuids;

    protected $fillable = [
        'external_id',
        'symbol',
        'name',
    ];

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'platform_coins')
            ->withPivot('contract_address');
    }
}
