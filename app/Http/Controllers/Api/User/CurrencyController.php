<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Eloquent\CurrencyService;
use App\Traits\Response;

class CurrencyController extends Controller
{
    use Response;

    private $currencyService;

    public function __construct()
    {
        $this->currencyService = new CurrencyService;
    }

    public function getAll()
    {
        return $this->success('Currencies', $this->currencyService->getAll());
    }
}
