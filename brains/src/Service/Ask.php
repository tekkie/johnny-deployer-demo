<?php

namespace App\Service;

use App\Rest;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

class Ask
{

    /**
     * @var SerializerInterface;
     */
    private $serializer;

    /**
     * @var \GuzzleHttp\Client;
     */
    private $watsonClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Ask constructor.
     *
     * @param SerializerInterface $serializer
     * @param Rest\Watson         $watsonClient
     */
    public function __construct(
            SerializerInterface $serializer,
            Rest\Watson $watsonClient,
            LoggerInterface $logger
    )
    {
        $this->serializer = $serializer;
        $this->watsonClient = $watsonClient;
        $this->logger = $logger;
    }

    /**
     * Figure out Watson's intent from the provided question
     *
     * @param string $question
     * @return \App\Entity\Watson\Response
     */
    public function ask(string $question): \App\Entity\Watson\Response
    {
        $request = new \App\Entity\Watson\Request($question);
        $reqBody = $this->serializer->serialize($request, 'json');

        $response = $this->watsonClient->request('post', '', ['body' => $reqBody]);

        $this->logger->debug(sprintf('Watson said: `%s`', $response->getBody()));

        /** @var \App\Entity\Watson\Response $deserialized */
        $deserialized = $this->serializer->deserialize(
            $response->getBody(),
            'App\Entity\Watson\Response',
            'json'
        );

        return $deserialized;
    }

}
