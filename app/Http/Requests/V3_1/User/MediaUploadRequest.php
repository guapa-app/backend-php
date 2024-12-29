<?php

namespace App\Http\Requests\V3_1\User;

use App\Http\Requests\FailedValidationRequest;
use App\Rules\ImageOrBase64;

class MediaUploadRequest  extends FailedValidationRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'media' => 'required|array|min:1',
            'media.*'         => ['required', new ImageOrBase64()],
        ];
    }
}
