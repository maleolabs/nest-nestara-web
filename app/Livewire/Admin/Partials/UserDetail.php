<?php

namespace App\Livewire\Admin\Partials;

trait UserDetail
{

    public $user_foto,
        $user_detail,
        $user_detail_id,
        $foto_is_default = true;

    public function saveNewFoto()
    {
        $this->getUserService()->saveFotoFromLivewire($this->user_detail_id, $this->user_foto->getClientOriginalPath());

        $this->user_foto = null;

        $this->setUpUserDetail($this->user_detail_id);
    }

    public function deleteFoto()
    {
        $this->getUserService()->deleteFoto($this->user_detail_id);

        $this->setUpUserDetail($this->user_detail_id);
    }

    protected function setUpUserDetail($user_id)
    {
        $this->user_detail = $this->getUserService()->find($user_id);
        $this->user_detail_id = $user_id;

        $this->foto_is_default = $this->user_detail->foto == $this->user_detail->default_foto;
    }

}