<?php

namespace LLenon\Payments\Efi;


use LLenon\Payments\Efi\Contracts\Credentials;
use LLenon\Payments\Efi\Contracts\Token;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EfiConnect
{
    private HttpClientInterface $httpClient;

    public function __construct(
        HttpClientInterface $httpClient,
    )
    {
        if (empty($_ENV['EFI_API_URL'])) {
            throw new   \InvalidArgumentException('EFI API URL is not defined; env key EFI_API_URL');
        }
        $this->httpClient = $httpClient->withOptions(['base_uri' => $_ENV['EFI_API_URL']]);
    }

    public function getConnection(Credentials $credentials): EfiConnection
    {
        $request = $this->httpClient->request('POST', $_ENV['EFI_API_URL'] . '/oauth/token', [
            'local_cert' => $credentials->certificatePath,
            'headers' => [
                'Authorization' => "Basic {$credentials->getAuthorization()}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $token = Token::fromArray($request->toArray());

        $http = $this->httpClient->withOptions([
                'local_cert' => $credentials->certificatePath,
                'base_uri' => $_ENV['EFI_API_URL'],
                'headers' => [
                    'Authorization' =>'Bearer '. $token->accessToken,
                    'Content-Type' => 'application/json',
                ]
            ]
        );

        return new EfiConnection($http,$token);
    }


}