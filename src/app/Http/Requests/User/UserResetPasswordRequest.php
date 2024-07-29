<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class UserResetPasswordRequest extends ApiRequest
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
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }
}
