@props([
    'paginator',
    'livewire' => true,
])

@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator */
    $pageName = $paginator->getPageName();
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();

    $pages = collect();
    for ($i = max(1, $current - 2); $i <= min($last, $current + 2); $i++) {
        $pages->push($i);
    }

    // navigasi via state Livewire (komponen) atau anchor biasa (halaman publik)
    $go = fn ($page) => $livewire
        ? "gotoPage({$page}, '{$pageName}')"
        : null;
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs font-semibold text-ink-500" @if($livewire) wire:loading.remove @endif>
            Menampilkan
            <span class="font-bold text-ink-800">{{ $paginator->firstItem() }}</span>–<span class="font-bold text-ink-800">{{ $paginator->lastItem() }}</span>
            dari <span class="font-bold text-ink-800">{{ number_format($paginator->total()) }}</span> data
        </p>
        @if ($livewire)
            <p class="text-xs font-bold text-nest-700 hidden" wire:loading>
                <i class="fa-solid fa-spinner fa-spin mr-1"></i> Memuat halaman…
            </p>
        @endif

        <div class="flex items-center gap-1" @if($livewire) wire:loading.attr="disabled" wire:loading.class="opacity-50 pointer-events-none" @endif>
            {{-- prev --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-300 ring-1 ring-ink-200 cursor-not-allowed"><i class="fa-solid fa-chevron-left text-xs"></i></span>
            @else
                @if ($livewire)
                    <button type="button" wire:click="{{ $go($current - 1) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left text-xs"></i></a>
                @endif
            @endif

            @if (! $pages->contains(1))
                @if ($livewire)
                    <button type="button" wire:click="{{ $go(1) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-bold bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition">1</button>
                @else
                    <a href="{{ $paginator->url(1) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-bold bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition">1</a>
                @endif
                <span class="px-1 text-ink-400">…</span>
            @endif

            @foreach ($pages as $page)
                @if ($livewire)
                    <button type="button" wire:click="{{ $go($page) }}"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-bold transition {{ $page == $current ? 'bg-ink-900 text-white shadow-sm' : 'bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700' }}"
                        @if($page == $current) aria-current="page" @endif>{{ $page }}</button>
                @else
                    <a href="{{ $paginator->url($page) }}"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-bold transition {{ $page == $current ? 'bg-ink-900 text-white shadow-sm' : 'bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700' }}"
                        @if($page == $current) aria-current="page" @endif>{{ $page }}</a>
                @endif
            @endforeach

            @if (! $pages->contains($last))
                <span class="px-1 text-ink-400">…</span>
                @if ($livewire)
                    <button type="button" wire:click="{{ $go($last) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-bold bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition">{{ $last }}</button>
                @else
                    <a href="{{ $paginator->url($last) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm font-bold bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition">{{ $last }}</a>
                @endif
            @endif

            {{-- next --}}
            @if ($paginator->hasMorePages())
                @if ($livewire)
                    <button type="button" wire:click="{{ $go($current + 1) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                @else
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right text-xs"></i></a>
                @endif
            @else
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-300 ring-1 ring-ink-200 cursor-not-allowed"><i class="fa-solid fa-chevron-right text-xs"></i></span>
            @endif
        </div>
    </nav>
@endif
