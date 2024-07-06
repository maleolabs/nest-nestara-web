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
            observeSelect2Value()
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
        function observeSelect2Value() {
            const select2El = document.querySelector('.select2-selection__rendered')
            if (select2El) {
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'characterData' || mutation.type === 'childList') {
                            @this.set('data_izin_form.jenis_izin', select2El.textContent)
                        }
                    });
                });
                const config = {
                    childList: true,
                    subtree: true,
                    characterData: true
                }
                observer.observe(select2El, config)
            }
        }
    </script>
@endpush