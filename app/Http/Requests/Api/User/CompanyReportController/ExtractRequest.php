<?php

namespace App\Http\Requests\Api\User\CompanyReportController;

use App\Http\Requests\Api\BaseApiRequest;

class ExtractRequest extends BaseApiRequest
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
            'date' => 'required',
        ];
    }
}
