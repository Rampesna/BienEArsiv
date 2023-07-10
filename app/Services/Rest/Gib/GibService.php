<?php

namespace App\Services\Rest\Gib;

use App\Services\Rest\Gib\Models\GibInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
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
        $this->testMode = true;
    }

    public function checkResponse(
        $jsonDecodedResponse
    )
    {
        return isset($jsonDecodedResponse->error) ?
            ['error' => true, 'message' => $jsonDecodedResponse->messages[0]->text] :
            ['error' => false, 'message' => 'Success'];
    }

    public function getBaseUrl()
    {
        return $this->testMode == true ? $this->testUrl : $this->baseUrl;
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
            "assoscmd" => $this->testMode == true ? "login" : "anologin",
            "rtype" => "json",
            "userid" => $this->taxNumber,
            "sifre" => $this->password,
            "sifre2" => $this->password,
            "parola" => $this->password
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->tokenEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        $check = $this->checkResponse(json_decode($response->getBody()));

        if ($check['error']) {
            return null;
        }

        return $this->token = json_decode($response->getBody())->token;
    }

    /**
     * @param string $token
     * @param string $uuid
     */
    public function getInvoiceFromAPI(
        string $token,
        string $uuid
    )
    {
        $data = [
            "ettn" => $uuid
        ];

        $parameters = [
            "cmd" => "EARSIV_PORTAL_FATURA_GETIR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITFATURA",
            "token" => $token,
            "jp" => "" . json_encode($data) . "",
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        return json_decode($response->getBody());
    }

    /**
     * @param GibInvoice $invoice
     * @param string $token
     */
    public function createInvoice(
        GibInvoice $invoice,
        string     $token
    )
    {
        if ($invoice == null) {
            throw new \Exception("Invoice variable not exist");
        }

        $parameters = [
            "cmd" => "EARSIV_PORTAL_FATURA_OLUSTUR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITFATURA",
            "token" => $token,
            "jp" => "" . json_encode($invoice->export()) . ""
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        return $response->getBody();
    }

    /**
     * @param string $taxNumber
     * @param string $token
     */
    public function getCustomerFromGibByTaxNumber(
        string $taxNumber,
        string $token
    )
    {
        $parameters = [
            "cmd" => "SICIL_VEYA_MERNISTEN_BILGILERI_GETIR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITFATURA",
            "token" => $token,
            "jp" => "" . json_encode(["vknTcknn" => $taxNumber,]) . ""
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
     * @param string $token
     */
    public function outbox(
        $startDatetime,
        $endDatetime,
        $token
    )
    {
        $parameters = [
            "cmd" => "EARSIV_PORTAL_TASLAKLARI_GETIR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITTASLAKLAR",
            "token" => $token,
            "jp" => '{"baslangic":"' . $startDatetime . '","bitis":"' . $endDatetime . '","hangiTip":"5000/30000", "table":[]}'
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => []
        ]);

        return json_decode($response->getBody())->data;
    }

    /**
     * @param string $startDatetime
     * @param string $endDatetime
     * @param string $token
     */
    public function inbox(
        $startDatetime,
        $endDatetime,
        $token
    )
    {
        $parameters = [
            "cmd" => "EARSIV_PORTAL_ADIMA_KESILEN_BELGELERI_GETIR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_ALICI_TASLAKLAR",
            "token" => $token,
            "jp" => '{"baslangic":"' . $startDatetime . '","bitis":"' . $endDatetime . ' " }'];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => []
        ]);

        return json_decode($response->getBody())->data;
    }

    /**
     * @param string $uuid
     * @param string $token
     * @param boolean $signed
     */
    public function getInvoiceHTML(
        $uuid,
        $token,
        $signed = true,
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
            "token" => $token,
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
     * @param string $token
     * @param boolean $signed
     */
    public function getInvoicePDF(
        $uuid,
        $token,
        $signed = true
    )
    {
//        $invoiceHtml = $this->getInvoiceHTML($uuid, $token, $signed);
//        $path = 'documents/eInvoices/';
//        $checkPath = public_path($path);
//        if (!file_exists($checkPath)) {
//            mkdir($checkPath, 0777, true);
//        }
//        $wkp = new \mikehaertl\wkhtmlto\Pdf();
//        $wkp->addPage($invoiceHtml);
//        $wkp->saveAs($path . $uuid . '.pdf');
//        $pdf = app()->make('dompdf.wrapper');
//        $pdf->loadHTML($invoiceHtml);
//        $pdf->save($checkPath . $uuid . '.pdf');
//        $pdf = PDF::loadHTML($invoiceHtml);
//        $pdf->save($checkPath . $uuid . '.pdf');

        $invoiceHtml = $this->getInvoiceHTML($uuid, $token, $signed);
        $path = 'public/documents/eInvoices/';
        $checkPath = base_path($path);
        if (!file_exists($checkPath)) {
            mkdir($checkPath, 0777, true);
        }
//        $wkp = new \mikehaertl\wkhtmlto\Pdf();
//        $wkp->addPage($invoiceHtml);
//        $wkp->saveAs($checkPath . $uuid . '.pdf');


        $dompdf = new Dompdf();
        $dompdf->loadHtmlFile($invoiceHtml);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream($uuid . '.pdf', array("Attachment" => false));


        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadHTML($invoiceHtml);
        $pdf->save($checkPath . $uuid . '.pdf');
        $pdf = PDF::loadHTML($invoiceHtml);
        $pdf->save($checkPath . $uuid . '.pdf');

        return 'documents/eInvoices/' . $uuid . '.pdf';

//        return $path . $uuid . '.pdf';
    }

    /**
     * Initialize SMS Verification
     *
     * @return boolean
     */

    private function initializeSmsVerification(
        $token
    )
    {
        $parameters = [
            "cmd" => "EARSIV_PORTAL_TELEFONNO_SORGULA",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITTASLAKLAR",
            "token" => $token,
            "jp" => "{}",
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        $data = json_decode($response->getBody())->data;

        return $data->telefon ?? $data['telefon'];
    }


    /**
     * @param string $token
     */
    public function sendSmsVerification(
        string $token,
        string $uuid,
    )
    {
        $getPhoneNumber = $this->initializeSmsVerification($token);

        $data = [
            "CEPTEL" => $getPhoneNumber,
            "KCEPTEL" => false,
            "TIP" => ""
        ];

        $parameters = [
            "cmd" => "EARSIV_PORTAL_SMSSIFRE_GONDER",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_SMSONAY",
            "token" => $token,
            "jp" => "" . json_encode($data) . "",
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        $data = json_decode($response->getBody())->data;

        $oid = $data->oid ?? $data["oid"];

        return $oid;
    }

    /**
     * @param string $token
     * @param string $code
     * @param string $oid
     * @param string $uuid
     */
    public function verifySmsCode(
        $token,
        $code,
        $oid,
        $uuid
    )
    {
        $invoice = $this->getInvoiceFromAPI($token, $uuid)->data;

        $gibInvoice = [
            "faturaOid" => "",
            "toplamTutar" => "0",
            "belgeNumarasi" => $invoice->belgeNumarasi,
            "aliciVknTckn" => $invoice->vknTckn,
            "aliciUnvanAdSoyad" => "",
            "saticiVknTckn" => "",
            "saticiUnvanAdSoyad" => "",
            "belgeTarihi" => date('d-m-Y', strtotime($invoice->faturaTarihi)),
            "belgeTuru" => "FATURA",
            "onayDurumu" => "Onaylanmadı",
            "ettn" => $invoice->faturaUuid,
            "talepDurumColumn" => "----------",
            "iptalItiraz" => -99,
            "talepDurum" => -99
        ];

        $data = [
            'SIFRE' => $code,
            'OID' => $oid,
            'OPR' => 1,
            'DATA' => [
                $gibInvoice
            ]
        ];

        $parameters = [
            "cmd" => "0lhozfib5410mp",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_SMSONAY",
            "token" => $token,
            "jp" => "" . json_encode($data) . "",
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->dispatchEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        $responseData = json_decode($response->getBody());

        if (isset($responseData->data->sonuc) && $responseData->data->sonuc == "1") {
            return true;
        } else if (isset($responseData->data['sonuc']) && $responseData->data['sonuc'] == "1") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $token
     * @param string $uuid
     * @param string $reason
     */
    public function cancelEInvoice(
        string $token,
        string $uuid,
        string $reason = "Yanlış İşlem"
    )
    {
        $invoice = $this->getInvoiceFromAPI($token, $uuid)->data;

        $gibInvoice = [
            "ettn" => $invoice->faturaUuid,
            "onayDurumu" => "Onaylandı",
            "belgeTuru" => "FATURA",
            "talepAciklama" => $reason,
        ];

        $parameters = [
            "cmd" => "EARSIV_PORTAL_IPTAL_TALEBI_OLUSTUR",
            "callid" => Uuid::uuid1()->toString(),
            "pageName" => "RG_BASITTASLAKLAR",
            "token" => $token,
            "jp" => "" . json_encode($gibInvoice) . "",
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->tokenEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        $responseData = json_decode($response->getBody());

        if ($responseData['data'] == "İptal talebiniz başarıyla oluşturulmuş ve kabul edilmiştir.") {
            return true;
        }

        return false;
    }

    public function logout(
        $token
    )
    {
        $parameters = [
            "assoscmd" => "logout",
            "rtype" => "json",
            "token" => $token,
        ];

        $response = $this->client->post($this->getBaseUrl() . $this->tokenEndpoint, [
            "form_params" => $parameters,
            "headers" => $this->headers
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
