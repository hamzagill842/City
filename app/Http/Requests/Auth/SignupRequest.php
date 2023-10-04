<?php

namespace App\Http\Requests\Auth;



use App\Http\Requests\BaseRequest;
use App\Rules\AgeLessThanTen;

class SignupRequest extends BaseRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => [
                'required',
                'date_format:Y-m-d',
                new AgeLessThanTen(),
            ]
        ];
    }
}
