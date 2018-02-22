<?php

namespace App\Rest;

use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class Watson
{
    /**
     * @var \GuzzleHttp\Client;
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(string $endpoint, string $username, string $password, LoggerInterface $logger)
    {
        $this->client = new \GuzzleHttp\Client([
            'auth' => [
                $username,
                $password
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'verify' => false,
            'base_uri' => $endpoint,
        ]);

        $this->logger = $logger;
        $this->logger->debug(sprintf('Initialising endpoint `%s` with `%s:%s`', $endpoint, $username, $password));
    }

    public function request(string $method, string $uri, array $configuration)
    {
        try {
//            $configuration['debug'] = true;
            $this->logger->debug(sprintf(
                'Talking `%s` with Watson on `%s` with `%s`',
                $method,
                $uri,
                print_r($configuration, true)
            ));

            $response = $this->client->request(
                $method,
                $uri,
                $configuration
            );

            return $response;
        } catch (RequestException $exception) {
            throw new \App\Exception\Rest\Watson('', 0, $exception);
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
