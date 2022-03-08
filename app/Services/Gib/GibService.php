<?php

namespace App\Services\Gib;

use App\Services\Gib\Models\GibInvoice;
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
        $this->client = new \GuzzleHttp\Client();
    }

    public function getBaseUrl()
    {
        return $this->testMode === true ? $this->testUrl : $this->baseUrl;
    }

    public function setTaxNumber($taxNumber)
    {
        $this->taxNumber = $taxNumber;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    public function setTestMode($status)
    {
        $this->testMode = $status;
    }

    private function checkError($jsonData)
    {
        if (isset($jsonData["error"])) {
            throw new \Exception($jsonData["error"]);
        }
    }

    public function setCredentials($taxNumber = null, $password = null)
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
}
