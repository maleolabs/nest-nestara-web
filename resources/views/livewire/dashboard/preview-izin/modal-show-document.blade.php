<div x-data="{ open: false }" x-on:set-preview-img-modal.window="open = true" x-show="open" x-cloak x-trap.noscroll="open" x-on:keydown.escape.window="open = false" class="fixed inset-0 z-[100] flex items-center justify-center bg-ink-900/70 p-4" role="dialog" aria-modal="true" aria-labelledby="showDocumentImgModalLabel">
    <div class="flex max-h-full w-full max-w-6xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl" @click.outside="open = false">
            <div class="flex items-start justify-between gap-4 border-b border-ink-100 px-5 py-4">
                <h1 class="text-lg font-extrabold text-ink-900" id="showDocumentImgModalLabel">{{ $modalImgTitle }}</h1>
                @if ($modalPdfSource)
                    <p class="text-right text-xs text-ink-500">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        PDF ini dalam mode <span class="italic">read only</span>.
                    </p>
                @endif
            </div>
            <div class="min-h-0 flex-1 overflow-auto p-4 sm:p-6">
                @if ($modalImgSource)
                    <img src="{{ \App\Support\SignedFileUrl::make(base64_decode($modalImgSource, true) ?: $modalImgSource) }}" class="max-h-[70vh] w-full object-contain" alt="{{ $modalImgTitle }}">
                @elseif($modalPdfSource)
                    <div class="flex justify-center">
                        <div id="pdf-container"></div>
                    </div>
                @endif
            </div>
            <div class="flex justify-end border-t border-ink-100 p-4">
                <button type="button" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-ink-100 text-ink-800 hover:bg-ink-200" @click="open = false">Tutup</button>
            </div>
        </div>
    </div>
</div>
