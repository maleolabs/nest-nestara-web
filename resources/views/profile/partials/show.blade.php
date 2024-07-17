<div class="{{ Route::currentRouteName() == 'profile.pemohon' ? 'container' : 'container-fluid' }} mt-4">
    <div class="card">
        <div class="card-header border-bottom">
            <h6 class="card-title mb-0">Profil</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-sm-5 d-flex flex-column align-items-center">
                    <div class="position-relative">
                        <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="" class="img-fluid img-rounded img-rounded-lg img-absolute">
                    </div>
                    <div class="mt-3 d-flex gap-2 justify-content-center">
                        <button class="btn btn-light" type="button">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-9 col-sm-7">

                </div>
            </div>
        </div>
    </div>
</div>