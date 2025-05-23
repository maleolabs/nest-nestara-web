<div class="row">
    @foreach ($entri_data as $entri)
        <div class="{{ $entri->tipe == 'textarea' ? 'col-12' : 'col-md-6' }} mb-3">
            <small class="text-muted">{{ $entri->nama }}</small>
            @if ($entri->is_multi_value && count(explode($entri->value_separator, $entri_data_izin[$entri->id])) > 1)
                @foreach (explode($entri->value_separator, $entri_data_izin[$entri->id]) as $item)
                    <h6 class="mb-0">{{ $loop->iteration . '. ' . $item }}</h6>
                @endforeach
            @else
                <h6 class="mb-0">{{ $entri->tipe == 'date' ? Carbon\Carbon::parse($entri_data_izin[$entri->id])->translatedFormat('d F Y') : $entri_data_izin[$entri->id] }}</h6>
            @endif
        </div>
    @endforeach
</div>