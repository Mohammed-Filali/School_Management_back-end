<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
            'name'     => 'required|max:50',
            'date_of_birth'=> 'required|date',
            'gender'       => ['required', Rule::in(['m', 'f'])],
            // 'last_login'   => 'date',
            'blood_Type' => ['required', Rule::in(array_map('strtoupper', ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+']))],
            'adress'       => 'required|max:255',
            'phone'        => 'required|max:10|',
            'email'        => 'required|email|',
            'password'     => 'required',
            'classe_id'     => 'required',
        ];
    }
}
