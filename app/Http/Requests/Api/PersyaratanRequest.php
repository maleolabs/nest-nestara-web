<?php

namespace App\Http\Requests\Api;

use App\Services\DomainServices\JenisPerizinanService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PersyaratanRequest extends FormRequest
{

    protected JenisPerizinanService $jenisPerizinanService;

    public function __construct(JenisPerizinanService $jenisPerizinanService)
    {
        parent::__construct();
        $this->jenisPerizinanService = $jenisPerizinanService;
    }

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
        $jenis_perizinan_id = $this->input('jenis_perizinan_id');
        
        $persyaratan = $this->jenisPerizinanService->getDataPersyaratan($jenis_perizinan_id);

        $rules = [];

        foreach ($persyaratan as $syarat) {
            $field_rules = [];
            if ($syarat->required) {
                
            }
        }

        return [
            //
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->getMessageBag()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}