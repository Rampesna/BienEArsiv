<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\DistrictController\GetByProvinceIdRequest;
use App\Services\Eloquent\DistrictService;
use App\Traits\Response;

class DistrictController extends Controller
{
    use Response;

    private $districtService;

    public function __construct()
    {
        $this->districtService = new DistrictService;
    }

    public function getAll()
    {
        return $this->success('Districts', $this->districtService->getAll());
    }

    public function getByProvinceId(GetByProvinceIdRequest $request)
    {
        return $this->success('Districts', $this->districtService->getByProvinceId($request->provinceId));
    }
}
