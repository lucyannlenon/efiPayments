<?php

namespace LLenon\Payments\Efi\Contracts;

class Credentials
{

    public function __construct(
        public string $certificatePath,
        public string $clientId,
        public string $clientSecret
    )
    {
    }

    public function getAuthorization(): string
    {
        return  base64_encode("{$this->clientId}:{$this->clientSecret}");
    }
    public function toArray(): array
    {
        return [
            'certificate_path' => $this->certificatePath,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];
    }
}