<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email:rfc|unique:users,email|max:255',
            'phone' =>  'required|unique:users,phone|regex:#^\+380\d{9}$#',
            'position_id' => 'required|integer|exists:positions,id',
            'photo' => 'required|image|mimes:jpeg,jpg|dimensions:min_width=70,min_height=70|max:5000',
        ];
    }

    /**
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if($errors->has('email') && $errors->first('email') === 'The email has already been taken.' ||
            $errors->has('phone') && $errors->first('phone') === 'The phone has already been taken'){
           throw new HttpResponseException(response()->json([
               'success' => false,
               'message' => 'User with this phone or email already exist'
           ],409));
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'fails' => $errors
        ],422));
    }
}
