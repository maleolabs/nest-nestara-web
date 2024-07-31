<?php

namespace App\Livewire\Admin\Partials;

trait UserDetail
{

    public $user_foto;

    public function saveNewFoto()
    {
        $this->getUserService()->saveFotoFromLivewire($this->user_detail->id, $this->user_foto->getClientOriginalPath());

        $this->user_foto = null;

        $this->user_detail = $this->getUserService()->find($this->user_detail_id);
    }

}