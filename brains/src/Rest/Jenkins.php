<?php

namespace App\Rest;

use GuzzleHttp\Exception\RequestException;

class Jenkins
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    private $credentials;

    public function __construct(string $endpoint, string $username, string $password)
    {
        $this->credentials = [
            $username,
            $password
        ];

        $this->client = new \GuzzleHttp\Client([
            'auth' => $this->credentials,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'verify' => false,
            'base_uri' => $endpoint,
        ]);
    }

    public function request(string $method, string $uri, array $configuration)
    {
        try {
            $response = $this->client->request(
                $method,
                $uri,
                $configuration
            );

            return $response;
        } catch (RequestException $exception) {
            $message = sprintf(
                'Calling `%s` with `%s` configured as `%s`',
                $uri,
                json_encode($configuration),
                implode(':', $this->credentials)
            );
            throw new \App\Exception\Rest\Jenkins($message, 0, $exception);
        }
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient(): \GuzzleHttp\Client
    {
        return $this->client;
    }

}
