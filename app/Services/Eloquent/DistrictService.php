<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\District;

class DistrictService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new District);
    }

    /**
     * @param int $provinceId
     */
    public function getByProvinceId(
        $provinceId
    )
    {
        return District::where('province_id', $provinceId)->get();
    }
}
