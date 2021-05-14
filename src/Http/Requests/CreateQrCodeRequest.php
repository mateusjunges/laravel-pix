<?php

namespace Junges\Pix\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQrCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'transaction_id' => ['required', 'string', 'max:25'],
            'key'            => ['required', 'string'],
            'merchant_name'  => ['required', 'string', 'max:25'],
            'merchant_city'  => ['required', 'string', 'max:15'],
            'description'    => ['sometimes', 'string', 'max:40'],
            'amount'         => ['required'],
        ];
    }
}
