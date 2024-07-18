<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\EntriData\EntriPenMasSeeder;
use Database\Seeders\EntriData\EntriPerekamMedisSeeder;
use Database\Seeders\EntriData\EntriSIPATLMSeeder;
use Database\Seeders\EntriData\EntriSIPPSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(PersyaratanIzinSeeder::class);

        $this->call(EntriPenMasSeeder::class);
        $this->call(EntriPerekamMedisSeeder::class);
        $this->call(EntriSIPPSeeder::class);
        $this->call(EntriSIPATLMSeeder::class);

        $this->call(PengaturanSeeder::class);
    }
}
