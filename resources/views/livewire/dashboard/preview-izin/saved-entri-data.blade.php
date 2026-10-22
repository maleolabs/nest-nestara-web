<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    @foreach ($entri_data as $entri)
        <div class="{{ $entri->tipe == 'textarea' ? 'min-w-0' : 'min-w-0' }} rounded-xl bg-ink-50 p-3">
            <small class="text-ink-500">{{ $entri->nama }}</small>
            @if ($entri->is_multi_value && count(explode($entri->value_separator, $entri_data_izin[$entri->id])) > 1)
                @foreach (explode($entri->value_separator, $entri_data_izin[$entri->id]) as $item)
                    <h6 class="">{{ $loop->iteration . '. ' . $item }}</h6>
                @endforeach
            @else
                <h6 class="">{{ $entri->tipe == 'date' ? Carbon\Carbon::parse($entri_data_izin[$entri->id])->translatedFormat('d F Y') : $entri_data_izin[$entri->id] }}</h6>
            @endif
        </div>
    @endforeach
</div>