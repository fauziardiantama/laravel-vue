<?php

namespace App\Http\Requests;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreProposalTA extends FormRequest
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
                'judul_proposal' => 'required',
                'tahun' => 'required',
                'semester_id' => 'required|exists:semester,id',
                'file_proposal' => 'required|file|mimes:pdf'
            ];
        } else {
            return [
                'judul_proposal' => 'required',
                'tahun' => 'required',
                'semester_id' => 'required|exists:semester,id',
                'file_proposal' => 'required|file|mimes:pdf'
            ];
        }
    }
}
