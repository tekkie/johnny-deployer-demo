<?php

namespace App\Rest;

use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class Jira
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    private $credentials;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
            string $endpoint,
            string $username,
            string $password,
            LoggerInterface $logger
    )
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
        $this->logger = $logger;
    }

    public function post($endpoint, $criteria)
    {
        return $this->request(
            'POST',
            $endpoint,
            $criteria
        );
    }

    public function request(string $method, string $uri, array $configuration)
    {
        try {
            $this->logger->debug(sprintf(
                'Doing a `%s` to `%s` with configuration `%s`',
                $method,
                $this->client->getConfig('base_uri') . $uri,
                json_encode($configuration)
            ));
            $response = $this->client->request(
                $method,
                $this->client->getConfig('base_uri') . $uri,
                [
                    'body' => json_encode($configuration)
                ]
            );

            $this->logger->debug(sprintf(
                'Got back status `%s` with body `%s`',
                $response->getStatusCode(),
                $response->getBody()
            ));

            return $response;
        } catch (RequestException $exception) {
            $this->logger->debug(sprintf(
                'Got back status `%s` and response `%s`',
                $exception->getResponse()->getStatusCode(),
                $exception->getResponse()->getBody()
            ));

            $message = sprintf(
                'Calling `%s` with `%s` configured as `%s`',
                $uri,
                json_encode($configuration),
                implode(':', $this->credentials)
            );
            throw new \App\Exception\Rest\Jira($message, 0, $exception);
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
