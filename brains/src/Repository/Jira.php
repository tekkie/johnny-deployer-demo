<?php

namespace App\Repository;

use App\Entity\Jira\Response\Issues;
use App\Entity\Jira\Issue;
//use App\Entity\Response\Errors as ErrorsResponse;
//use App\Exception\Forbidden;
//use App\Exception\Api;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Rest\Jira as RestClient;

class Jira
{
    private $fields = [];

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var \App\Rest\Jira
     */
    private $client;

    public function __construct(SerializerInterface $serializer, RestClient $restClient)
    {
        $this->fields = [
            'summary',
            'description',
            'status',
            'assignee',
            'issuetype',
            'updated',
            'labels',
        ];

        $this->serializer = $serializer;
        $this->client = $restClient;
    }

    /**
     * Search for issues according to supplied $jql search string.
     *      If no $fields supplied, we'll use our own extended list.
     *
     * @return Issues
     */
    public function search(string $jql = '', array $fields = []): Issues
    {
        $criteria = [
            'jql' => $jql,
            'startAt' => '0',
            'maxResults' => '-1', // all
            'fields' => count($fields) ? $fields : $this->fields
        ];

        $response = $this->client->post('/api/2/search/', $criteria);

        if (Response::HTTP_NOT_FOUND == $response->getStatusCode()) {
            return new Issues(); // just an empty list
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new \App\Exception\Rest\Jira('Expired session!');
        }

        /** @var \App\Entity\Jira\Response\Issues $issues */
        $issues = $this->serializer->deserialize($response->getBody(), Issues::class, 'json');

        return $issues;
    }

}
