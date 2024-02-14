<?php

namespace App\Http\Requests;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\StudentEmail;
use App\Rules\PhoneNumber;

class RegisterMahasiswaRequest extends FormRequest
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
            'nim' => 'required|string|unique:mahasiswa',
            'nama' => 'required|string',
            'email' => ['required','email','unique:mahasiswa', new StudentEmail],
            'no_telp' => ['required','string', new PhoneNumber],
            'password' => ['required','string','min:8','confirmed', new Password]
        ];
    }
}
