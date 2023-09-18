<?php

namespace App\Http\Requests\User;

use App\Rules\AgeLessThanTen;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user = $this->user(); //
        return [
            'name' => 'required|string',
            'city' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'date_of_birth' => [
                'required',
                'date_format:Y-m-d',
                new AgeLessThanTen(),
            ]
        ];
    }
}
