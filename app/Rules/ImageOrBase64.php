<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ImageOrBase64 implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Validate if the value is a valid uploaded image file
        $validator = Validator::make([$attribute => $value], [
            $attribute => 'image',
        ]);

        if ($validator->passes()) {
            return true; // Passes as an image file
        }

        // Validate if the value is a base64 encoded image string
        if (is_string($value) && preg_match('/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/', $value)) {
            return true; // Passes as a valid Base64 image
        }

        return false; // Fails both checks
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid image file or a valid Base64-encoded image string.';
    }
}

