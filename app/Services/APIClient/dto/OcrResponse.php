<?php

namespace App\Services\APIClient\dto;

class OcrResponse
{
    private array $data;

    private bool $success;

    private ?string $errorMessage;

    public function __construct(array $data, bool $success, ?string $errorMessage)
    {
        $this->data = $data;
        $this->success = $success;
        $this->errorMessage = $errorMessage;
    }

    public static function failure(?string $message = null): self
    {
        return new self([], false, $message);
    }

    public static function fromJson($json): ?OcrResponse
    {
        if (is_null($json)) {
            return null;
        }

        $success = $json->success ?? false;
        $errorMessage = $json->errorMessage ?? null;

        $data = [];

        if (! is_null($json->data)) {
            foreach ($json->data as $k => $v) {
                $data[$k] = $v;
            }
        }

        return new OcrResponse($data, $success, $errorMessage);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'success' => $this->success,
            'errorMessage' => $this->errorMessage,
        ];
    }
}
