<?php

namespace App\Http\Requests\Api\User\InvoiceProductController;

use App\Http\Requests\Api\BaseApiRequest;

class CreateRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoiceId' => 'required',
            'productId' => 'required',
            'quantity' => 'required',
            'unitId' => 'required',
            'unitPrice' => 'required',
            'vatRate' => 'required',
            'discountRate' => 'required',
        ];
    }
}
