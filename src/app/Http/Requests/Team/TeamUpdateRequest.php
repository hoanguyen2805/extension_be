<?php

namespace App\Http\Requests\Team;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class TeamUpdateRequest extends ApiRequest
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
            'id' => ['required', 'exists:teams'],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'min:2', 'max:255'],
            'data' => ['nullable', 'json'],
        ];
    }
}
