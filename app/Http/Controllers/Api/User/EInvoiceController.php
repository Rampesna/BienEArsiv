<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\EInvoiceController\LoginRequest;
use App\Http\Requests\Api\User\EInvoiceController\LogoutRequest;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoiceHTMLRequest;
use App\Http\Requests\Api\User\EInvoiceController\GetInvoicePdfRequest;
use App\Http\Requests\Api\User\EInvoiceController\OutboxRequest;
use App\Http\Requests\Api\User\EInvoiceController\InboxRequest;
use App\Http\Requests\Api\User\EInvoiceController\SendSmsVerificationRequest;
use App\Http\Requests\Api\User\EInvoiceController\VerifySmsCodeRequest;
use App\Http\Requests\Api\User\EInvoiceController\CancelEInvoiceRequest;
use App\Services\Eloquent\CustomerService;
use App\Services\Eloquent\InvoiceService;
use App\Services\Rest\Gib\GibService;
use App\Traits\Response;

class EInvoiceController extends Controller
{
    use Response;

    private $gibService;

    private $customerService;

    private $invoiceService;

    public function __construct()
    {
        $this->gibService = new GibService;
        $this->customerService = new CustomerService;
        $this->invoiceService = new InvoiceService;
    }

    public function login(LoginRequest $request)
    {
        $this->gibService->setCredentials($request->user()->customer->gib_code, $request->user()->customer->gib_password);
        $token = $this->gibService->login();

        if (!$token) {
            return $this->error('EInvoice Login Failed', 401);
        }

        return $this->success('EInvoice Login Successful', $this->customerService->updateGibToken($request->user()->customer->id, $token));
    }

    public function logout(LogoutRequest $request)
    {
        $this->gibService->logout(
            $request->user()->customer->gib_token
        );

        return $this->success('EInvoice Logout Successful', $this->customerService->updateGibToken($request->user()->customer->id));
    }

    public function outbox(OutboxRequest $request)
    {
        return $this->success('eInvoices', $this->gibService->outbox(
            date('d/m/Y', strtoTime($request->dateStart)),
            date('d/m/Y', strtoTime($request->dateEnd)),
            $request->user()->customer->gib_token
        ));
    }

    public function inbox(InboxRequest $request)
    {
        return $this->success('eInvoices', $this->gibService->inbox(
            date('d/m/Y', strtoTime($request->dateStart)),
            date('d/m/Y', strtoTime($request->dateEnd)),
            $request->user()->customer->gib_token
        ));
    }

    public function getInvoiceHTML(GetInvoiceHTMLRequest $request)
    {
        return $this->success('eInvoice HTML', $this->gibService->getInvoiceHTML(
            $request->uuid,
            $request->user()->customer->gib_token
        ));
    }

    public function getInvoicePDF(GetInvoicePdfRequest $request)
    {
        $this->gibService->getInvoicePDF(
            $request->uuid,
            $request->user()->customer->gib_token
        );
    }

    public function sendSmsVerification(SendSmsVerificationRequest $request)
    {
        return $this->gibService->sendSmsVerification(
            $request->user()->customer->gib_token,
            $request->uuid
        );
    }

    // Siz stok istiyorsunuz onu yazılım ekiine iletmemiz lzım

    public function verifySmsCode(VerifySmsCodeRequest $request)
    {
        $gibResponse = $this->gibService->verifySmsCode(
            $request->user()->customer->gib_token,
            $request->smsCode,
            $request->oid,
            $request->uuid
        );

        if ($gibResponse == true) {
            $invoice = $this->invoiceService->getByUuid($request->uuid);
            $invoice->status_id = 3;
            $invoice->save();
            $this->success('Invoice approved successfully', null);
        } else {
            $this->error('Invoice not approved', 400);
        }
    }

    public function cancelEInvoice(CancelEInvoiceRequest $request)
    {
        return $this->gibService->cancelEInvoice(
            $request->user()->customer->gib_token,
            $request->uuid
        ) == true ?
            $this->success('eInvoice cancelled successfully', null) :
            $this->error('eInvoice not cancelled', 400);
    }
}
