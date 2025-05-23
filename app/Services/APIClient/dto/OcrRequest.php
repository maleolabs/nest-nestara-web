<?php

namespace App\Services\APIClient\dto;

class OcrRequest
{

    private $izinId;

    private $jenisPerizinanId;

    private $syaratIzinId;

    private $imageUrl;

    private $requiredKeys;

    private static ?OcrRequestBuilder $ocrRequestBuilder = null;

    public function __construct($izinId, $jenisPerizinanId, $syaratIzinId, $imageUrl, $requiredKeys = []) 
    {
        $this->izinId = $izinId;
        $this->jenisPerizinanId = $jenisPerizinanId;
        $this->syaratIzinId = $syaratIzinId;
        $this->imageUrl = $imageUrl;
        $this->requiredKeys = $requiredKeys;
    }

    public function getIzinId()
    {
        return $this->izinId;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function getRequiredKeys()
    {
        $requiredKeys = [];
        foreach ($this->requiredKeys as $key) {
            $requiredKeys[] = [
                'name' => 'requiredKeys[]',
                'contents' => $key
            ];
        }

        return $requiredKeys;
    }

    public static function builder(): OcrRequestBuilder
    {
        if (self::$ocrRequestBuilder === null) {
            self::$ocrRequestBuilder = new OcrRequestBuilder;
        }
        return self::$ocrRequestBuilder;
    }

}

class OcrRequestBuilder
{

    private $izinId;

    private $jenisPerizinanId;

    private $syaratIzinId;

    private $imageUrl;

    private $requiredKeys = [];

    public function setIzinId(int $izinId): self
    {
        $this->izinId = $izinId;
        return $this;
    }

    public function setJenisPerizinanId(int $jenisPerizinanId): self
    {
        $this->jenisPerizinanId = $jenisPerizinanId;
        return $this;
    }

    public function setSyaratIzinId(int $syaratIzinId): self
    {
        $this->syaratIzinId = $syaratIzinId;
        return $this;
    }

    public function setImageUrl(String $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function setRequiredKeys(array $requiredKeys): self
    {
        $this->requiredKeys = $requiredKeys;
        return $this;
    }

    public function build(): OcrRequest
    {
        return new OcrRequest(
            $this->izinId,
            $this->jenisPerizinanId,
            $this->syaratIzinId,
            $this->imageUrl,
            $this->requiredKeys
        );
    }

}