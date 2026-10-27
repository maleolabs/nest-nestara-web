<?php

namespace Tests\Unit\Livewire;

use App\Livewire\Dashboard\BackOffice\Penetapan;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Support\Collection;
use App\Models\PermitLetterGeneration;
use Tests\TestCase;

class PenetapanTest extends TestCase
{
    public function test_old_printed_permit_remains_in_printing_list_for_reprint(): void
    {
        $component = new Penetapan;
        $component->all_izin = [
            (object) [
                'status_izin' => new Collection([
                    (object) [
                        'nama' => StatusIzinEnum::PROSES,
                        'progres' => StatusIzinProgressEnum::FINISHED,
                        'keterangan' => BackOfficeStatusEnum::DICETAK,
                         'updated_at' => now()->subDays(2),
                     ],
                 ]),
                 'permitLetterGenerations' => new Collection([new PermitLetterGeneration(['status' => PermitLetterGeneration::COMPLETED, 'path' => 'x.pdf'])]),
             ],
        ];

        $method = new \ReflectionMethod($component, 'getFilteredDataList');
        $method->setAccessible(true);

        self::assertCount(1, $method->invoke($component, 'Pembuatan Izin'));
    }
}
