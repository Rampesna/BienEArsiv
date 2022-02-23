<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\CountryService;
use App\Traits\Response;

class CountryController extends Controller
{
    use Response;

    private $countryService;

    public function __construct()
    {
        $this->countryService = new CountryService;
    }

    public function getAll()
    {
        return $this->success('Countries', $this->countryService->getAll());
    }
}
