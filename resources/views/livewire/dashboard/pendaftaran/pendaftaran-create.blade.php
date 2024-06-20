<div class="content-wrap border-top border-1 p-sm-4">
    @if ($type == 'data-pemohon')
        @livewire('dashboard.pendaftaran.data-pemohon-create')
    @elseif($type == 'izin-baru')
        @livewire('dashboard.pendaftaran.data-izin-create')
    @endif
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            select2Init()
        });
        Livewire.hook('morph.updated', ({ el, component }) => {
            select2Init()
            @if(count($errors) <= 0)
                inputsHandle()
            @endif
        })
        function select2Init() {
            $('#jenisPerizinanSelect').select2({
                placeholder: 'Pilih jenis perizinan',
                theme: "bootstrap-5",
            });
        }
    </script>
@endpush