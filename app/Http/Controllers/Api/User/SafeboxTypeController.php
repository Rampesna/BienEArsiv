<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\SafeboxTypeService;
use App\Traits\Response;

class SafeboxTypeController extends Controller
{
    use Response;

    private $safeboxTypeService;

    public function __construct()
    {
        $this->safeboxTypeService = new SafeboxTypeService;
    }

    public function getAll()
    {
        return $this->success('Safebox types', $this->safeboxTypeService->getAll());
    }
}
