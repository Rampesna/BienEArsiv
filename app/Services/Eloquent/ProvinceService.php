<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\Province;

class ProvinceService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Province);
    }

    /**
     * @param int $countryId
     */
    public function getByCountryId(
        $countryId
    )
    {
        return Province::where('country_id', $countryId)->get();
    }
}
