<?php

namespace App\Http\Requests\Api\User\InvoiceController;

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
            'companyId' => 'required',
            'typeId' => 'required',
            'currencyId' => 'required',
            'currency' => 'required',
            'vatDiscountId' => 'required',
            'datetime' => 'required',
            'vatIncluded' => 'required',
        ];
    }
}
