<?php

namespace App\Http\Requests;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalTA extends FormRequest
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
                'nim' => 'string|exists:mahasiswa,nim',
                'judul_proposal' => 'string',
                'tahun' => 'string',
                'semester_id' => 'exists:semester,id',
                'file_proposal' => 'file|mimes:pdf'
            ];
        } else {
            return [
                'judul_proposal' => 'string',
                'tahun' => 'string',
                'semester_id' => 'exists:semester,id',
                'file_proposal' => 'file|mimes:pdf'
            ];
        }
    }
}
