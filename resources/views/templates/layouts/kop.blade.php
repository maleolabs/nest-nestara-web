<div class="mb-1">
    <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path($kop_surat['logo_path']))) }}" width="75" height="75" style="float: left">
    <div class="text-center my-auto ms-3" style="float: left">
        <p class="fs-4 text-bold mb-1">{{ $kop_surat['nama_pemerintah'] }}</p>
        <p class="fs-5">{{ $kop_surat['nama_dinas'] }}</p>
        <p class="fs-6 mb-0">{{ $kop_surat['alamat'] }}</p>
        <p class="fs-6 mb-0">Website : {{ $kop_surat['website'] }}</p>
    </div>
</div>
<div class="clear-fix"></div>
<div class="garis-horizontal"></div>
<div class="garis-horizontal-sm"></div>