<?php

namespace App\Http\Requests;

use App\Models\SystemSetting;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends FormRequest
{
    use ApiResponser;

    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $handleErrors = [];

        foreach ($errors as $field => $message) {
            $handleErrors[] = [
                'field' => $field,
                'message' => $message[0],
            ];
        }
        $errorFirst = $validator->errors()->first();
        $errorCount = $validator->errors()->count();

        throw new HttpResponseException(response()->json([
            'message'  =>  $errorCount <= 1 ? $errorFirst : $errorFirst . '(and ' . $errorCount - 1 . ' more error)',
            'errors'  => $handleErrors,
            'status' => 422
        ], 422));
    }
}
