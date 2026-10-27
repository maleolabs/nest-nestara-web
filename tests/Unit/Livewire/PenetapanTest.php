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

    public function test_printed_permit_with_completed_letter_is_handover_eligible(): void
    {
        $component = new \App\Livewire\Dashboard\Penyerahan\PenyerahanIndex;
        $property = new \ReflectionProperty($component, 'all_izin');
        $property->setAccessible(true);
        $property->setValue($component, collect([(object) [
            'status_izin' => new Collection([(object) ['keterangan' => BackOfficeStatusEnum::DICETAK]]),
            'permitLetterGenerations' => new Collection([
                new PermitLetterGeneration(['status' => PermitLetterGeneration::COMPLETED, 'path' => 'x.pdf']),
            ]),
        ]]));

        self::assertCount(1, $property->getValue($component));
    }

    public function test_further_discussion_permit_stays_in_penetapan_queue(): void
    {
        $component = new Penetapan;
        $component->all_izin = [(object) ['status_izin' => new Collection([(object) ['keterangan' => BackOfficeStatusEnum::BAHAS_LANJUT]])]];
        $method = new \ReflectionMethod($component, 'getFilteredDataList');
        $method->setAccessible(true);
        self::assertCount(1, $method->invoke($component, 'Penetapan Izin'));
    }
}
