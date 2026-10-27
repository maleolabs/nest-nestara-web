<?php

namespace Database\Factories;

use App\Models\Pemohon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemohon>
 */
class PemohonFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Pemohon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sumber_identitas' => 'ktp',
            'nomor_id' => fake()->randomNumber(),
            'nama_pemohon' => fake()->name(),
            'no_telepon' => fake()->numerify('08##########'),
            'provinsi' => 75,
            'kabupaten' => 7571,
            'kecamatan' => 7571030,
            'kelurahan' => 7571030012,
            'detail_alamat' => fake()->streetAddress()
        ];
    }
    
}
