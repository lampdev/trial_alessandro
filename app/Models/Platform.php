<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $id
 * @property string $external_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Platform extends Model
{
    use HasUuids;

    protected $fillable = [
        'external_id',
    ];

    public function coins(): BelongsToMany
    {
        return $this->belongsToMany(Coin::class, 'platform_coins')
            ->withPivot('contract_address');
    }
}
