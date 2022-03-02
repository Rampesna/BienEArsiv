<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ProvinceController\GetByCountryIdRequest;
use App\Services\Eloquent\ProvinceService;
use App\Traits\Response;

class ProvinceController extends Controller
{
    use Response;

    private $provinceService;

    public function __construct()
    {
        $this->provinceService = new ProvinceService;
    }

    public function getAll()
    {
        return $this->success('Provinces', $this->provinceService->getAll());
    }

    public function getByCountryId(GetByCountryIdRequest $request)
    {
        return $this->success('Provinces', $this->provinceService->getByCountryId($request->countryId));
    }
}
