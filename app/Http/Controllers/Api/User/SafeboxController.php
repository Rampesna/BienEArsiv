<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\SafeboxController\AllRequest;
use App\Http\Requests\Api\User\SafeboxController\IndexRequest;
use App\Http\Requests\Api\User\SafeboxController\GetByIdRequest;
use App\Http\Requests\Api\User\SafeboxController\GetTotalBalanceRequest;
use App\Http\Requests\Api\User\SafeboxController\CreateRequest;
use App\Http\Requests\Api\User\SafeboxController\UpdateRequest;
use App\Http\Requests\Api\User\SafeboxController\DeleteRequest;
use App\Services\Eloquent\SafeboxService;
use App\Traits\Response;

class SafeboxController extends Controller
{
    use Response;

    private $safeboxService;

    public function __construct()
    {
        $this->safeboxService = new SafeboxService;
    }

    public function all(AllRequest $request)
    {
        return $this->success('Safeboxes', $this->safeboxService->all(
            $request->user()->customer_id
        ));
    }

    public function index(IndexRequest $request)
    {
        return $this->success('Safeboxes', $this->safeboxService->index(
            $request->user()->customer_id,
            $request->pageIndex,
            $request->pageSize,
            $request->keyword,
            $request->safeboxType,
        ));
    }

    public function getById(GetByIdRequest $request)
    {
        $safebox = $this->safeboxService->getById($request->id);
        return !$safebox || $request->user()->customer_id != $safebox->customer_id
            ? $this->error('Safebox not found', 404)
            : $this->success('Safebox details', $safebox);
    }

    public function getTotalBalance(GetTotalBalanceRequest $request)
    {
        return $this->success('Total balance', $this->safeboxService->getTotalBalance(
            $request->user()->customer_id,
            $request->typeId
        ));
    }

    public function create(CreateRequest $request)
    {
        return $this->success('Safebox created successfully', $this->safeboxService->create(
            $request->user()->customer_id,
            $request->typeId,
            $request->name,
            $request->accountNumber,
            $request->branch,
            $request->iban,
        ));
    }

    public function update(UpdateRequest $request)
    {
        $safeBox = $this->safeboxService->getById($request->id);

        if (!$safeBox || ($request->user()->customer_id != $safeBox->customer_id)) {
            return $this->error('Safebox not found', 404);
        }

        return $this->success('Safebox created successfully', $this->safeboxService->update(
            $safeBox->id,
            $request->user()->customer_id,
            $request->typeId,
            $request->name,
            $request->accountNumber,
            $request->branch,
            $request->iban,
        ));
    }

    public function delete(DeleteRequest $request)
    {
        $safeBox = $this->safeboxService->getById($request->id);

        if (!$safeBox || ($request->user()->customer_id != $safeBox->customer_id)) {
            return $this->error('Safebox not found', 404);
        }

        return $this->success('Safebox created successfully', $this->safeboxService->delete(
            $safeBox->id
        ));
    }
}
