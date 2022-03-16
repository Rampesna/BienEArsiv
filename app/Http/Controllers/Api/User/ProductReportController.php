<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ProductReportController\AllRequest;
use App\Services\Eloquent\ProductReportService;
use App\Traits\Response;

class ProductReportController extends Controller
{
    use Response;

    private $productService;

    public function __construct()
    {
        $this->productReportService = new ProductReportService;
    }

    public function all(AllRequest $request)
    {
        return $this->success('Products', $this->productReportService->all(
            $request->user()->customer_id
        ));
    }
}
