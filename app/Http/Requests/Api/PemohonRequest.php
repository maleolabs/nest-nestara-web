<?php

namespace App\Http\Requests\Api;

use App\Services\APIClient\RegionAPIService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class PemohonRequest extends FormRequest
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
            'sumber_identitas' => ['required'],
            'nomor_id' => ['required', 'max:20'],
            'nama_pemohon' => ['required'],
            'no_telepon' => ['required'],
            'provinsi' => ['required'],
            'kabupaten' => ['required'],
            'kecamatan' => ['required'],
            'kelurahan' => ['required'],
            'detail_alamat' => ['required'],
            'alamat_luar_negeri' => ['nullable'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $province = (int) $this->input('provinsi');
            $regency = (int) $this->input('kabupaten');
            $district = (int) $this->input('kecamatan');
            $village = (int) $this->input('kelurahan');
            $regions = app(RegionAPIService::class);

            if (! $this->containsId($regions->getProvinces(), $province)) {
                $validator->errors()->add('provinsi', 'Provinsi tidak ditemukan.');

                return;
            }

            if (! $this->containsId($regions->getRegenciesByProvince($province), $regency)) {
                $validator->errors()->add('kabupaten', 'Kabupaten tidak sesuai dengan provinsi.');

                return;
            }

            if (! $this->containsId($regions->getDistrictsByRegency($regency), $district)) {
                $validator->errors()->add('kecamatan', 'Kecamatan tidak sesuai dengan kabupaten.');

                return;
            }

            if (! $this->containsId($regions->getVillagesByDistrict($district), $village)) {
                $validator->errors()->add('kelurahan', 'Kelurahan tidak sesuai dengan kecamatan.');
            }
        });
    }

    private function containsId(array $regions, int $id): bool
    {
        return collect($regions)->contains(fn (array $region): bool => (int) ($region['id'] ?? 0) === $id);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
