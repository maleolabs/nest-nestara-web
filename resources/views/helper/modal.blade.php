@if(isset($modal))
<div class="dash-modal-container" style="{{ $modal->isShow() || $errors->any() ? 'display: flex;' : 'display: none;' }}" id="modal">
    <div class="dash-modal position-relative {{ !$modal->isClosable() ? 'dash-modal-fixed' : 'dash-modal-closable' }}">
        <div class="py-3 border-bottom sticky-top-0 bg-white" style="z-index: 9">
            <h5 class="text-center">{{ $modal->getTitle() }}</h5>
        </div>
        <div class="dash-modal-body position-relative p-4">
            @if(count($modal->getForm()) > 0)
                <form @if($modal->isComponent())wire:submit.prevent="{{ $modal->getSubmitFunction() }}" @else action="{{ $modal->getSubmitFunction() }}" @endif>
                    @csrf
                    @foreach ($modal->getForm() as $form)
                        <div class="row justify-content-center mb-3">
                            @if ($form['type'] == 'checkbox')
                                <div class="form-check col-sm-10">
                                    <input type="checkbox" class="form-check-input" id="{{ $form['name'] }}" @if($modal->isComponent()) wire:model="{{ $form['model'] . (isset($form['id']) && $form['id'] !== null ? '.' . $form['id'] : '') }}" @endif>
                                    <label for="{{ $form['name'] }}" class="form-check-label" style="text-transform: capitalize">{{ $form['name'] }}</label>
                                </div>
                                @if($modal->isComponent())
                                    @if($errors->has($form['model']))
                                        <small class="text-danger"><i>{{ $errors->first($form['model']) }}</i></small>
                                    @endif
                                @else
                                    @if($errors->has($form['name']))
                                        <small class="text-danger"><i>{{ $errors->first($form['name']) }}</i></small>
                                    @endif
                                @endif
                            @elseif($form['type'] == 'radio')
                                @if($modal->isComponent())
                                    <div class="col-sm-10">
                                        @php
                                            $var = $form['model'];
                                            $var2 = $$var;
                                        @endphp
                                        <div class="form-check app-form-check w-100 flex-column {{ $var2 == $form['value'] ? 'active' : '' }}">
                                            <input class="form-check-input app-form-check-input" type="radio" name="{{ $form['name'] }}" wire:model.live='{{ $form['model'] }}' value="{{ $form['value'] }}" id="{{ $form['id'] . '-' . $loop->iteration }}">
                                            <label class="form-check-label fw-semibold d-block" for="{{ $form['id'] . '-' . $loop->iteration }}">
                                                {{ $form['value'] }}
                                            </label>
                                        </div>
                                    </div>
                                    <div wire:loading>
                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-white opacity-75 d-flex align-items-center justify-content-center">
                                            <div class="spinner-border" role="status"></div>
                                            <span role="status">Loading...</span>
                                        </div>
                                    </div>
                                @endif
                            @elseif($form['type'] == 'sub-form')
                                <h6 class="col-sm-10 fw-bold">{{ $form['name'] }}</h6>
                            @elseif($form['type'] == 'textarea')
                                @php($label = str_replace('_', ' ', $form['name']))
                                <label for="{{ $form['name'] }}" class="col-sm-10 form-label" style="text-transform: capitalize">{{ Lang::has('messages.' . $label) ? __('messages.' . $label) : $label }}</label>
                                <div class="col-sm-10">
                                    <textarea rows="3" id="{{ $form['name'] }}" @if($modal->isComponent())wire:model="{{ isset($form['model']) ? $form['model'] : $form['name'] }}" @else name="{{ $form['name'] }}" @endif class="form-control"></textarea>
                                    @if($errors->has($form['name']))
                                        <small class="text-danger"><i>{{ $errors->first($form['name']) }}</i></small>
                                    @endif
                                </div>
                            @else
                                @php($label = str_replace('_', ' ', $form['name']))
                                <label for="{{ $form['name'] }}" class="col-sm-10 form-label" style="text-transform: capitalize">{{ Lang::has('messages.' . $label) ? __('messages.' . $label) : $label }}</label>
                                <div class="col-sm-10">
                                    <input type="{{ $form['type'] }}" id="{{ $form['name'] }}" @if($modal->isComponent())wire:model="{{ isset($form['model']) ? $form['model'] : $form['name'] }}" @else name="{{ $form['name'] }}" @endif class="form-control">
                                    @if($errors->has($form['name']))
                                        <small class="text-danger"><i>{{ $errors->first($form['name']) }}</i></small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="row justify-content-center mt-2">
                        <div class="col-sm-10">
                            <div class="d-flex align-items-center justify-content-center">
                                <button class="btn btn-secondary shadow-none" @if($modal->isComponent()) wire:click='{{ $modal->getCancelFunction() }}' @else onclick="document.querySelector('.dash-modal-container').style.display='none'" @endif type="button">{{ $modal->getCancelButton() }}</button>
                                <button class="ms-2 btn btn-app-primary" type="submit">{{ $modal->getApplyButton() }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            @elseif(count($modal->getConfirms()) > 0)
                <div class="row justify-content-center">
                    @foreach ($modal->getConfirms() as $confirm)
                        @if($confirm['key'] == 'decision')
                            <p class="col-sm-10 text-center">{{ $confirm['value'] }}</p>
                        @else
                            <div class="col-sm-10">{{ __('messages.' . $confirm['key']) }}</div>
                            <div class="fw-semibold col-sm-10 mb-3">{{ $confirm['value'] }}</div>
                        @endif
                    @endforeach
                </div>
                <div class="d-flex align-items-center justify-content-center mt-2">
                    <button class="btn btn-secondary shadow-none" @if($modal->isComponent()) wire:click='{{ $modal->getCancelFunction() }}' @else onclick="document.querySelector('.dash-modal-container').style.display='none'" @endif type="button">{{ $modal->getCancelButton() }}</button>
                    <button class="ms-2 btn {{ $modal->getApplyButtonBgColor() ? $modal->getApplyButtonBgColor() : 'btn-app-primary' }}" wire:click='{{ $modal->getSubmitFunction() }}'>{{ $modal->getApplyButton() }}</button>
                </div>
            @else
                <div class="text-center">
                    @if($modal->isSuccess())<i class="text-success fs-2 fa-solid fa-circle-check"></i>@else<i class="text-danger fs-2 fa-solid fa-circle-xmark"></i>@endif<p class="mt-3">{{ $modal->getMessage() }}</p>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button class="btn btn-secondary" @if($modal->isComponent()) wire:click="{{ $modal->getCancelFunction() }}" @endif @if($modal->getCancelFunction()) wire:click='{{ $modal->getCancelFunction() }}' @else onclick="document.querySelector('.dash-modal-container').style.display='none'" @endif>{{ $modal->getCancelButton() }}</button>
                </div>
            @endif
        </div>
    </div>
</div>
@endif