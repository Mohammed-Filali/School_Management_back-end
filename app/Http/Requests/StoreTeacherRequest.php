<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
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
        'firsName'     => 'required|max:50',
        'lastName' => 'required|max:50',
        'date_of_birth'=> 'required|date',
        'blood_Type' => ['required', Rule::in(array_map('strtoupper', ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+']))],
        'adress'       => 'required|max:255',
        'gender'       => ['required', Rule::in(['m', 'f'])],
        'phone'        => 'required|max:10|unique:'.Teacher::class,
        'email'        => 'required|email|unique:'.Teacher::class,
        'password'     => 'required',
        'course_id'     => 'required'

        ];
    }
}
