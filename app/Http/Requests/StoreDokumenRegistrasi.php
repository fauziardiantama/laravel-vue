<?php

namespace App\Http\Requests;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreDokumenRegistrasi extends FormRequest
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
        if ($this->user()->isAdmin()) {
            return [
                'nim' => 'required|exists:mahasiswa,nim',
                'type' => 'required|in:KRS,KartuMahasiswa,Transkrip,BuktiSeminar',
                'file' => 'required|file|mimes:pdf'
            ];
        } else {
            return [
                'type' => 'required|in:KRS,KartuMahasiswa,Transkrip,BuktiSeminar',
                'file' => 'required|file|mimes:pdf'
            ];
        }
    }
}
