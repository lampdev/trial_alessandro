<?php

namespace App\Services;

use App\Models\Platform;

class PlatformManagementService
{
    /**
     * Returns Platform by external_id or stores new one if platform is missing.
     *
     * @param  string  $externalID
     * @return Platform
     */
    public function findOrCreateByExternalId(string $externalID): Platform
    {
        return Platform::firstOrCreate(['external_id' => $externalID]);
    }
}
