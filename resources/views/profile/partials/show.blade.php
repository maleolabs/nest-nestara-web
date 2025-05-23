<div class="{{ Route::currentRouteName() == 'profile.pemohon' ? 'container' : (Route::currentRouteName() != 'profile.admin' ? 'container-fluid' : '') }} {{ Route::currentRouteName() != 'profile.admin' ? 'mt-4' : '' }}">
    @livewire('helper.profile')
</div>