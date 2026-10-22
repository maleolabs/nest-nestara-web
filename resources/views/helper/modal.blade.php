@if(isset($modal))
@php($modalOpen = $modal->isShow() || $errors->any())
@php($modalId = 'modal-' . substr(md5(($modal->getTitle() ?? 'modal') . '-' . spl_object_id($modal)), 0, 10))
<div x-data="{ open: @js($modalOpen) }" x-init="$watch('open', value => document.body.classList.toggle('overflow-hidden', value))" x-show="open" x-cloak
    @keydown.escape.window="if (@js($modal->isClosable())) open = false"
    @click.self="if (@js($modal->isClosable())) open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-ink-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title">
    <div x-show="open" x-transition.opacity class="relative max-h-[min(90vh,720px)] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl ring-1 ring-ink-200 {{ !$modal->isClosable() ? 'pointer-events-auto' : '' }}">
        <div class="sticky top-0 z-10 border-b border-ink-200 bg-white px-6 py-4">
            <h2 id="{{ $modalId }}-title" class="text-center text-lg font-extrabold text-ink-900">{{ $modal->getTitle() }}</h2>
        </div>
        <div class="relative p-6">
            @if(count($modal->getForm()) > 0)
                <form @if($modal->isComponent()) wire:submit.prevent="{{ $modal->getSubmitFunction() }}" @else action="{{ $modal->getSubmitFunction() }}" method="POST" @endif class="space-y-4">
                    @csrf
                    @foreach ($modal->getForm() as $form)
                        @php($field = $form['name'])
                        @if ($form['type'] == 'checkbox')
                            <label class="flex items-center gap-3 text-sm font-semibold text-ink-800">
                                <input type="checkbox" id="{{ $modalId }}-{{ $loop->index }}-{{ $field }}" name="{{ $field }}" @if($modal->isComponent()) wire:model="{{ $form['model'] . (isset($form['id']) && $form['id'] !== null ? '.' . $form['id'] : '') }}" @endif class="h-4 w-4 rounded border-ink-300 text-nest-700 focus:ring-nest-500">
                                {{ ucfirst($field) }}
                            </label>
                        @elseif ($form['type'] == 'radio' && $modal->isComponent())
                            @php($var = $form['model']) @php($var2 = $$var)
                            <label class="block rounded-xl border p-3 text-sm font-semibold {{ $var2 == $form['value'] ? 'border-nest-600 bg-nest-50' : 'border-ink-200' }}">
                                <input type="radio" name="{{ $field }}" wire:model.live="{{ $form['model'] }}" value="{{ $form['value'] }}" class="mr-2 text-nest-700">{{ $form['value'] }}
                            </label>
                        @elseif ($form['type'] == 'sub-form')
                            <h3 class="font-bold text-ink-900">{{ $field }}</h3>
                        @else
                            @php($label = str_replace('_', ' ', $field))
                            <label for="{{ $modalId }}-{{ $loop->index }}-{{ $field }}" class="block text-sm font-semibold capitalize text-ink-800">{{ Lang::has('messages.' . $label) ? __('messages.' . $label) : $label }}</label>
                            @if($form['type'] == 'textarea')
                                <textarea rows="3" id="{{ $modalId }}-{{ $loop->index }}-{{ $field }}" @if($modal->isComponent()) wire:model="{{ $form['model'] ?? $field }}" @else name="{{ $field }}" @endif class="mt-1 w-full rounded-lg border border-ink-300 px-3 py-2 text-sm focus:border-nest-600 focus:outline-none focus:ring-2 focus:ring-nest-100"></textarea>
                            @else
                                <input type="{{ $form['type'] }}" id="{{ $modalId }}-{{ $loop->index }}-{{ $field }}" @if($modal->isComponent()) wire:model="{{ $form['model'] ?? $field }}" @else name="{{ $field }}" @endif class="mt-1 w-full rounded-lg border border-ink-300 px-3 py-2 text-sm focus:border-nest-600 focus:outline-none focus:ring-2 focus:ring-nest-100">
                            @endif
                            @if($errors->has($field)) <p class="mt-1 text-xs italic text-red-600">{{ $errors->first($field) }}</p> @endif
                        @endif
                    @endforeach
                    <div class="flex justify-center gap-2 pt-2">
                        <button type="button" @if($modal->isComponent()) wire:click="{{ $modal->getCancelFunction() }}" @else @click="open = false" @endif class="rounded-lg bg-ink-100 px-4 py-2 text-sm font-bold text-ink-700 hover:bg-ink-200">{{ $modal->getCancelButton() }}</button>
                        <button type="submit" class="rounded-lg bg-nest-700 px-4 py-2 text-sm font-bold text-white hover:bg-nest-800">{{ $modal->getApplyButton() }}</button>
                    </div>
                </form>
            @elseif(count($modal->getConfirms()) > 0)
                <div class="space-y-3 text-sm text-ink-800">@foreach ($modal->getConfirms() as $confirm)<div class="{{ $confirm['key'] == 'decision' ? 'text-center' : '' }}">{{ $confirm['key'] == 'decision' ? $confirm['value'] : __('messages.' . $confirm['key']) . ': ' . $confirm['value'] }}</div>@endforeach</div>
                @if($modal->isComponent())
                    <div class="mt-5 flex justify-center gap-2"><button type="button" @click="open = false" class="rounded-lg bg-ink-100 px-4 py-2 text-sm font-bold text-ink-700">{{ $modal->getCancelButton() }}</button><button type="button" wire:click="{{ $modal->getSubmitFunction() }}" class="rounded-lg bg-nest-700 px-4 py-2 text-sm font-bold text-white">{{ $modal->getApplyButton() }}</button></div>
                @else
                    <form action="{{ $modal->getSubmitFunction() }}" method="POST" class="mt-5 flex justify-center gap-2">
                        @csrf
                        <button type="button" @click="open = false" class="rounded-lg bg-ink-100 px-4 py-2 text-sm font-bold text-ink-700">{{ $modal->getCancelButton() }}</button>
                        <button type="submit" class="rounded-lg bg-nest-700 px-4 py-2 text-sm font-bold text-white">{{ $modal->getApplyButton() }}</button>
                    </form>
                @endif
            @else
                <div class="text-center"><p class="text-3xl {{ $modal->isSuccess() ? 'text-emerald-600' : 'text-red-600' }}">{{ $modal->isSuccess() ? '✓' : '×' }}</p><p class="mt-3 text-sm text-ink-700">{{ $modal->getMessage() }}</p></div>
                <div class="mt-5 flex justify-center"><button type="button" @if($modal->isComponent() && $modal->getCancelFunction()) wire:click="{{ $modal->getCancelFunction() }}" @else @click="open = false" @endif class="rounded-lg bg-ink-100 px-4 py-2 text-sm font-bold text-ink-700">{{ $modal->getCancelButton() }}</button></div>
            @endif
        </div>
    </div>
</div>
@endif
