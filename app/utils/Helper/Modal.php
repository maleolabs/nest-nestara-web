<?php

namespace App\utils\Helper;

class Modal {
    private $showModal, $title, $forms, $confirms, $message, $cancelBtn, $applyBtn, $applyBtnColor, $closable, $component, $submitFunction, $cancelFunction, $status;

    public function __construct(bool $closable, bool $component)
    {
        $this->forms = [];
        $this->confirms = [];
        $this->showModal = false;
        $this->component = $component;
        $this->closable = $closable;
    }

    public function isClosable()
    {
        return $this->closable;
    }

    public function isComponent()
    {
        return $this->component;
    }

    public function show()
    {
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function isShow()
    {
        return $this->showModal;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function addForm($type = 'text', $name, $model = null, $id = null, $value = null)
    {
        $this->forms[] = [
            'type' => strtolower($type),
            'name' => strtolower($name),
            'model' => $model,
            'id' => $id,
            'value' => $value
        ];
    }

    public function getForm()
    {
        return $this->forms;
    }

    public function addConfirm($key, $value)
    {
        $this->confirms[] = [
            'key' => $key,
            'value' => $value,
        ];
    }
    
    public function getConfirms()
    {
        return $this->confirms;
    }

    public function setCancelButton($title)
    {
        $this->cancelBtn = $title;
    }

    public function getCancelButton()
    {
        return $this->cancelBtn;
    }

    public function setApplyButton($title)
    {
        $this->applyBtn = $title;
    }

    public function getApplyButton()
    {
        return $this->applyBtn;
    }

    public function setApplyButtonBgColor($color)
    {
        $this->applyBtnColor = $color;
    }

    public function getApplyButtonBgColor()
    {
        return $this->applyBtnColor;
    }

    public function setSubmitFunction($function)
    {
        $this->submitFunction = $function;
    }

    public function getSubmitFunction()
    {
        return $this->submitFunction;
    }

    public function setCancelFunction($function)
    {
        $this->cancelFunction = $function;
    }

    public function getCancelFunction()
    {
        return $this->cancelFunction;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function isSuccess()
    {
        return $this->status;
    }
}