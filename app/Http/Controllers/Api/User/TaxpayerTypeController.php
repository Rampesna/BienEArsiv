<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\TaxpayerTypeService;
use App\Traits\Response;

class TaxpayerTypeController extends Controller
{
    use Response;

    private $taxpayerTypeService;

    public function __construct()
    {
        $this->taxpayerTypeService = new TaxpayerTypeService;
    }

    public function getAll()
    {
        return $this->success('Taxpayer types', $this->taxpayerTypeService->getAll());
    }
}
