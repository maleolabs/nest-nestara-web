<?php

namespace App\Services\APIClient\dto;

class OcrRequest
{

    private $izinId;

    private $jenisPerizinanId;

    private $syaratIzinId;

    private $imageUrl;

    private static ?OcrRequestBuilder $ocrRequestBuilder = null;

    public function __construct($izinId, $jenisPerizinanId, $syaratIzinId, $imageUrl)
    {
        $this->izinId = $izinId;
        $this->jenisPerizinanId = $jenisPerizinanId;
        $this->syaratIzinId = $syaratIzinId;
        $this->imageUrl = $imageUrl;
    }

    public static function builder(): OcrRequestBuilder
    {
        if (self::$ocrRequestBuilder === null) {
            self::$ocrRequestBuilder = new OcrRequestBuilder;
        }
        return self::$ocrRequestBuilder;
    }

    public function toArray()
    {
        return [
            'izinId' => $this->izinId,
            'jenisPerizinanId' => $this->jenisPerizinanId,
            'syaratIzinId' => $this->syaratIzinId,
            'imageUrl' => $this->imageUrl
        ];
    }

}

class OcrRequestBuilder
{

    private $izinId;

    private $jenisPerizinanId;

    private $syaratIzinId;

    private $imageUrl;

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

    public function build(): OcrRequest
    {
        return new OcrRequest(
            $this->izinId,
            $this->jenisPerizinanId,
            $this->syaratIzinId,
            $this->imageUrl
        );
    }

}