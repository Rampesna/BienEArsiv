<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\UnitService;
use App\Traits\Response;

class UnitController extends Controller
{
    use Response;

    private $unitService;

    public function __construct()
    {
        $this->unitService = new UnitService;
    }

    public function getAll()
    {
        return $this->success('Units', $this->unitService->getAll());
    }
}
