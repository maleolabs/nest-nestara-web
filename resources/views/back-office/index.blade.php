@extends('back-office.layouts.app')

@section('main')
    <div class="space-y-8 pb-10">
        <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-nest-700">Back office</p>
                <h1 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-ink-900">Dashboard</h1>
                <p class="mt-2 max-w-xl text-sm leading-6 text-ink-600">Pantau pemohon dan permohonan terbaru dalam satu tampilan.</p>
            </div>
            <a href="{{ route('izin.index') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-nest-700 px-4 py-2 text-sm font-bold text-white transition hover:bg-nest-800 focus:outline-none focus:ring-4 focus:ring-nest-100">
                Kelola semua izin <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
            </a>
        </header>

        <div class="grid gap-5 lg:grid-cols-[minmax(0,1.35fr)_minmax(280px,0.65fr)]">
            <section class="min-w-0 rounded-2xl border border-ink-200 bg-white shadow-sm" aria-labelledby="permohonan-heading">
                <div class="flex items-start justify-between gap-4 border-b border-ink-100 px-5 py-5 sm:px-6">
                    <div>
                        <h2 id="permohonan-heading" class="text-base font-bold text-ink-900">Permohonan terbaru</h2>
                        <p class="mt-1 text-sm text-ink-500">10 permohonan terakhir yang masuk.</p>
                    </div>
                    <span class="rounded-full bg-nest-50 px-3 py-1 text-xs font-bold text-nest-700">Terbaru</span>
                </div>
                <div class="divide-y divide-ink-100">
                    @forelse ($all_izin as $izin)
                        @php
                            $status = $izin->status_izin?->last();
                            $statusClasses = $status
                                ? getStatusIzinClasses($status)
                                : ['textClass' => 'text-muted', 'bgClass' => 'bg-muted'];
                            $pemohon = $izin->pemohon;
                            $namaPemohon = $pemohon->nama_pemohon ?? 'Pemohon';
                            $foto = $pemohon?->user?->foto ?: 'profile.png';
                        @endphp
                        <article class="group px-5 py-4 transition hover:bg-ink-50/60 sm:px-6">
                            <div class="flex items-start gap-3">
                                <img src="{{ asset('storage/users/' . $foto) }}" alt="Foto {{ $namaPemohon }}" class="img-circle img-circle-sm shrink-0">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                                        <div class="min-w-0">
                                            <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="block truncate text-sm font-bold text-ink-900 underline-offset-4 group-hover:text-nest-700 group-hover:underline focus:outline-none focus:ring-2 focus:ring-nest-200">
                            {{ str_capitalize($izin->jenis_perizinan?->nama ?? 'Perizinan', true) }}
                                            </a>
                                            <p class="mt-1 truncate text-xs text-ink-500">{{ $namaPemohon }} · {{ $izin->created_at?->diffForHumans() }}</p>
                                        </div>
                                        <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full bg-ink-50 px-2.5 py-1 text-xs font-semibold {{ $statusClasses['textClass'] }}">
                                            <i class="{{ $statusClasses['bgClass'] }}" aria-hidden="true"></i>{{ $status?->nama ?? 'Status belum tersedia' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <i class="fa-regular fa-folder-open text-2xl text-ink-300" aria-hidden="true"></i>
                            <p class="mt-3 text-sm font-semibold text-ink-700">Belum ada permohonan izin</p>
                            <p class="mt-1 text-sm text-ink-500">Permohonan baru akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-ink-200 bg-white shadow-sm" aria-labelledby="pemohon-heading">
                <div class="border-b border-ink-100 px-5 py-5">
                    <h2 id="pemohon-heading" class="text-base font-bold text-ink-900">Pemohon terbaru</h2>
                    <p class="mt-1 text-sm text-ink-500">20 pemohon terakhir yang terdaftar.</p>
                </div>
                <div class="max-h-[30rem] divide-y divide-ink-100 overflow-y-auto">
                    @forelse ($all_pemohon as $pemohon)
                        @php
                            $namaPemohon = $pemohon->nama_pemohon ?? 'Pemohon';
                            $user = $pemohon->user;
                            $identitas = collect([$pemohon->sumber_identitas, $pemohon->nomor_id])
                                ->filter(fn ($value) => filled($value))
                                ->join(' · ');
                        @endphp
                        <div class="flex items-start gap-3 px-5 py-3.5">
                            <img src="{{ asset('storage/users/' . ($user?->foto ?: 'profile.png')) }}" alt="Foto {{ $namaPemohon }}" class="img-circle h-9 w-9 shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-ink-900">{{ $namaPemohon }}</p>
                                <div class="mt-0.5 flex min-w-0 flex-wrap gap-x-2 gap-y-0.5 text-xs text-ink-500">
                                    @if ($identitas !== '')
                                        <span class="truncate">{{ $identitas }}</span>
                                    @endif
                                    @if (filled($pemohon->no_telepon))
                                        <span class="truncate">{{ $pemohon->no_telepon }}</span>
                                    @endif
                                </div>
                            </div>
                            @if ($pemohon->created_at)
                                <time class="shrink-0 pt-0.5 text-[11px] text-ink-400" datetime="{{ $pemohon->created_at->toIso8601String() }}">{{ $pemohon->created_at->diffForHumans() }}</time>
                            @endif
                        </div>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-ink-500">Belum ada data pemohon.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ $statistikJenisPerizinan->cdn() }}"></script>
    {{ $statistikJenisPerizinan->script() }}
@endpush
