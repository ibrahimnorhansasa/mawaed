<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;



class userUpdateRequest extends FormRequest
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


    protected function failedValidation(Validator $validator)
    {
   throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id=auth('api')->user()->id;
        
        return [
            'firstname'          => 'required',
            'lastname'          => 'required',
            'phone'         => 'required|string|unique:users,phone,'.$id,
            'email'         => 'required|string|unique:users,email'.$id,
            'password'      => 'required|string|min:3|max:255',  
       
        ];
    }
}