<?php

namespace App\Services\Rest\Gib;

use App\Services\Rest\Gib\Models\GibInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Ramsey\Uuid\Uuid;

class GibService
{
    private $baseUrl;

    private $testUrl;

    private $dispatchEndpoint;

    private $tokenEndpoint;

    private $referrerEndpoint;

    public $taxNumber;

    public $password;

    public $client;

    public $token;

    public $language = 'TR';

    public $referrer;

    public $testMode = false;

    public $invoices = [];

    public $headers = [
        "accept" => "*/*",
        "accept-language" => "tr,en-US;q=0.9,en;q=0.8",
        "cache-control" => "no-cache",
        "content-type" => "application/x-www-form-urlencoded;charset=UTF-8",
        "pragma" => "no-cache",
        "sec-fetch-mode" => "cors",
        "sec-fetch-site" => "same-origin",
        "User-Agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.67 Safari/537.36", // Dummy UA
    ];

    public function __construct()
    {
        $this->baseUrl = "https://earsivportal.efatura.gov.tr";
        $this->testUrl = "https://earsivportaltest.efatura.gov.tr";
        $this->dispatchEndpoint = "/earsiv-services/dispatch";
        $this->tokenEndpoint = "/earsiv-services/assos-login";
        $this->referrerEndpoint = "/intragiris.html";
        $this->client = new \GuzzleHttp\Client(['verify' => false]);
    }

    public function getBaseUrl()
    {
        return $this->testMode === true ? $this->testUrl : $this->baseUrl;
    }

    /**
     * @param string $taxNumber
     */
    public function setTaxNumber($taxNumber)
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @param string $referrer
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    /**
     * @param boolean $status
     */
    public function setTestMode($status)
    {
        $this->testMode = $status;
    }

    /**
     * @param string|null $taxNumber
     * @param string|null $password
     */
    public function setCredentials(
        $taxNumber = null,
        $password = null
    )
    {
        if ($taxNumber && $password) {
            $this->taxNumber = $taxNumber;
            $this->password = $password;
        } else {
            $response = $this->client->post($this->getBaseUrl() . "/earsiv-services/esign", [
                "form_params" => [
                    "assoscmd" => "kullaniciOner",
                    "rtype" => "json",
                ]
            ]);

            $this->taxNumber = json_decode($response->getBody())->userid;
            $this->password = "1";
        }
    }

    public function login()
    {
        $parameters = [
            "assoscmd" => $this->testMode === true ? "login" : "anologin",
            "rtype" => "json",
            "userid" => $this->taxNumber,
            "sifre" => $this->password,
            "sifre2" => $this->password,
            "parola" => "1"
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->tokenEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        return $this->token = json_decode($response->getBody())->token;
    }

    /**
     * @param GibInvoice $invoice
     */
    public function createInvoice(GibInvoice $invoice)
    {
        if ($invoice == null) {
            throw new \Exception("Invoice variable not exist");
        }

        $parameters = [
            "cmd" => "EARSIV_PORTAL_FATURA_OLUSTUR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITFATURA",
            "token" => $this->token,
            "jp" => "" . json_encode($invoice->export()) . ""
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        return $response->getBody();
    }

    /**
     * @param string $startDatetime
     * @param string $endDatetime
     */
    public function getInvoices(
        $startDatetime,
        $endDatetime
    )
    {
        $parameters = [
            "cmd" => "EARSIV_PORTAL_TASLAKLARI_GETIR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITTASLAKLAR",
            "token" => $this->token,
            "jp" => '{"baslangic":"' . $startDatetime . '","bitis":"' . $endDatetime . '","hangiTip":"5000/30000", "table":[]}'
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => []
        ]);

        return json_decode($response->getBody())->data;
    }

    /**
     * @param string $uuid
     * @param boolean $signed
     */
    public function getInvoiceHTML(
        $uuid,
        $signed = true
    )
    {
        $data = [
            "ettn" => $uuid,
            "onayDurumu" => $signed ? "Onaylandı" : "Onaylanmadı"
        ];

        $parameters = [
            "cmd" => "EARSIV_PORTAL_FATURA_GOSTER",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_TASLAKLAR",
            "token" => $this->token,
            "jp" => "" . json_encode($data) . "",
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        return json_decode($response->getBody())->data;
    }

    /**
     * @param string $uuid
     * @param boolean $signed
     */
    public function getInvoicePDF(
        $uuid,
        $signed = true
    )
    {
        $invoiceHtml = $this->getInvoiceHTML($uuid, $signed);
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadHTML($invoiceHtml);
        $pdf->save(public_path('eInvoices/' . $uuid . '.pdf'));


        $pdf = PDF::loadHTML($invoiceHtml);
        $pdf->save(public_path('eInvoices/' . $uuid . '.pdf'));
    }
}
