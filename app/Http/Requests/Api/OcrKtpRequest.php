<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class OcrKtpRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'mimetypes:image/jpeg,image/png', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'documentType' => ['nullable', 'string', 'max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'foto KTP wajib diunggah',
            'image.file' => 'foto KTP harus berupa file',
            'image.image' => 'file yang diunggah harus berupa gambar',
            'image.mimes' => 'format foto KTP harus jpg, jpeg, atau png',
            'image.mimetypes' => 'PDF tidak didukung. Unggah foto KTP dalam format JPG, JPEG, atau PNG',
            'image.max' => 'ukuran foto KTP maksimal 3MB',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
