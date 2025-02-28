<?php

namespace LLenon\Payments\Efi;

use LLenon\Payments\Efi\Contracts\Token;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EfiConnection
{


    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly Token               $token
    )
    {
    }

    public function getBalance(): float
    {
        $result = $this->httpClient->request('GET', '/v2/gn/saldo');
        $data = $result->toArray(false);
        return $data['saldo'];
    }

    /**
     * @param array $body
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function pixCreateImmediateCharge(array $body): array
    {
        $request = $this->httpClient->request('POST', '/v2/cob', [
            'json' => $body,
        ]);

        return $request->toArray();
    }

    public function pixGenerateQRCode(string $id): array
    {
        $request = $this->httpClient->request('GET', '/v2/loc/' . $id . '/qrcode');
        return $request->toArray();
    }

    public function pixDetailCharge(string $txId): array
    {
        $request = $this->httpClient->request('GET', '/v2/cob/' . $txId);
        return $request->toArray();
    }

    public function getNotification(string $token): array
    {
        if (empty($_ENV['EFI_API_URL_INVOICES'])) {
            throw new \InvalidArgumentException('EFI API URL is not defined or not defined in .env file key EFI_API_URL_INVOICES');
        }
        $url = $_ENV['EFI_API_URL_INVOICES'] . '/v1/notification/' . $token;


        $request = $this->httpClient->request('GET', $url);
        return $request->toArray();
    }

    public function createOneStepLink(array $body): array
    {
        if (empty($_ENV['EFI_API_URL_INVOICES'])) {
            throw new \InvalidArgumentException('EFI API URL is not defined or not defined in .env file key EFI_API_URL_INVOICES');
        }
        $url = $_ENV['EFI_API_URL_INVOICES'] . '/v1/charge/one-step/link';


        $request = $this->httpClient->request('POST', $url, [
            'json' => $body,
        ]);
        return $request->toArray();
    }

    public function pixListWebhook(array $params): array
    {
        $request = $this->httpClient->request('GET', '/v2/webhook', [
            'query' => $params
        ]);
        return $request->toArray();
    }

    public function pixConfigWebhook(string $key, array $body): array
    {
        $request = $this->httpClient->request('PUT', '/v2/webhook/' . $key, [
            'json' => $body
        ]);
        return $request->toArray();
    }

    public function pixResendWebhook(array $body):array
    {
        $request = $this->httpClient->request('POST', '/v2/gn/webhook/reenviar', [
            'json' => $body
        ]);
        return $request->toArray();
    }
}