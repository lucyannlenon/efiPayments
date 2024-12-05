<?php

namespace LLenon\Payments\Efi\Contracts;

class Token
{

    public readonly string $accessToken;
    public readonly string $tokenType;
    public readonly int $expiresIn;
    public readonly string $scope;

    /**
     * TokenDTO constructor.
     *
     * @param string $accessToken The access token string.
     * @param string $tokenType The type of token (e.g., Bearer).
     * @param int $expiresIn The validity duration in seconds.
     * @param string $scope The scope of the token.
     */
    public function __construct(
        string $accessToken,
        string $tokenType,
        int    $expiresIn,
        string $scope
    )
    {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
        $this->scope = $scope;
    }

    /**
     * Factory method to create a TokenDTO from an associative array.
     *
     * @param array $data The token data.
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['access_token'] ?? '',
            $data['token_type'] ?? '',
            $data['expires_in'] ?? 0,
            $data['scope'] ?? ''
        );
    }

    /**
     * Converts the DTO to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'scope' => $this->scope,
        ];
    }
}