<div class="content-wrap p-sm-4">
    <div class="container">
        @if ($type == 'data-pemohon')
            @livewire('dashboard.pendaftaran.data-pemohon-create')
        @elseif($type == 'izin-baru')
            @livewire('dashboard.pendaftaran.data-izin-create')
        @endif
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            select2Init()
        });
        Livewire.hook('morph.updated', ({ el, component }) => {
            select2Init()
        })
        function select2Init() {
            $('#jenisPerizinanSelect').select2({
                placeholder: 'Pilih jenis perizinan',
                theme: "bootstrap-5",
            });
        }
    </script>
@endpush