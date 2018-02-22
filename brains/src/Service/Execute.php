<?php

namespace App\Service;

use App\Entity\Jenkins\Build\Request;
use App\Exception\Rest\Jenkins;
use App\Rest;
use GuzzleHttp\Exception\RequestException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class Execute
{

    /**
     * @var SerializerInterface;
     */
    private $serializer;

    /**
     * @var \GuzzleHttp\Client;
     */
    private $restClient;

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
            Rest\Jenkins $restClient,
            LoggerInterface $logger
    )
    {
        $this->serializer = $serializer;
        $this->restClient = $restClient;
        $this->logger = $logger;
    }

    /**
     * Execute a non-parametrized job
     *
     * @param string $job
     * @param string $token
     *
     * @return string
     *
     * @throws Jenkins
     */
    public function execute(string $job): string
    {
        try {
            $uri = sprintf(
                '%s/%s/build?token=%s',
                $this->restClient->getClient()->getConfig('base_uri'),
                $job,
                getenv('JENKINS_TOKEN')
            );

            $response = $this->restClient->request('get', $uri, []);

            switch (true) {
                case $response->getStatusCode() == Response::HTTP_CREATED:
                    return '';
                    break;
                case $response->getStatusCode() !== Response::HTTP_OK:
                    throw new Jenkins('No idea what is going on in Jenkins');
                    break;
                default:
                    return $response->getBody()->__toString();
                    break;
            }
        } catch (RequestException $exception) {
            throw new Jenkins('', 0, $exception);
        }
    }

    /**
     * Execute a parametrized build
     *
     * @param string $job
     *
     * @return string
     *
     * @throws Jenkins
     */
    public function executeWithParameters(string $job, Request $parameters): string
    {
        try {
            $uri = sprintf(
                '%s/job/%s/buildWithParameters?token=%s',
                getenv('JENKINS_ENDPOINT'),
                $job,
                'build_and_deploy'
            );

            foreach ($parameters->getParameters() as $parameter) {
                $uri = sprintf(
                    '%s&%s=%s',
                    $uri,
                    $parameter->getName(),
                    $parameter->getValue()
                );
            }
            $this->logger->debug(sprintf('Calling parametrized build: %s', $uri));

            $response = $this->restClient->request(
                'post',
                $uri,
                []
            );

            switch (true) {
                case $response->getStatusCode() == Response::HTTP_CREATED:
                    return '';
                    break;
                case $response->getStatusCode() !== Response::HTTP_OK:
                    throw new Jenkins('No idea what is going on in Jenkins');
                    break;
                default:
                    return $response->getBody()->__toString();
                    break;
            }
        } catch (RequestException $exception) {
            throw new Jenkins('', 0, $exception);
        }
    }

    public function history(string $component, string $environment)
    {
        try {
            $uri = sprintf(
                '%s/view/all/job/2_Prepare_RC_and_Deploy/api/json?pretty=true&depth=2&tree=builds[actions[parameters[name,value]],displayName,url,result,timestamp]',
                str_replace(
                    '/view/Johnny/job',
                    '',
                    $this->restClient->getClient()->getConfig('base_uri')
                )
            );
            $this->logger->debug(sprintf('Calling out `%s`', $uri));

            $response = $this->restClient->request('get', $uri, []);

            switch (true) {
                case $response->getStatusCode() === Response::HTTP_OK:
                    /** @var \App\Entity\Jenkins\Response $allJobs */
                    $allJobs = $this->serializer->deserialize(
                        $response->getBody(),
                        'App\Entity\Jenkins\Response',
                        'json'
                    );

                    foreach ($allJobs->getBuilds() as $build) {
                        // parse each individual build
                        foreach ($build->getActions() as $action) {
                            if ('hudson.model.ParametersAction' !== $action->getClass()) {
                                continue;
                            }

                            if (! $action->matches($component, $environment)) {
                                continue;
                            }

                            return sprintf(
                                ' It was on %s and was marked as %s. <%sconsole|See the details>',
                                date('Y-m-d H:i:s', $build->getTimestamp()),
                                $build->getResult(),
                                str_replace(
                                    getenv('JENKINS_ENDPOINT'),
                                    getenv('JENKINS_ENDPOINT_PUBLIC'),
                                    $build->getUrl()
                                )
                            );
                        }
                    }

                    return sprintf(
                        ' Apologies, I could not find any deployment of %s in %s',
                        $component,
                        $environment
                    );
                    break;
                default:
                    return $response->getBody()->__toString();
                    break;
            }
        } catch (RequestException $exception) {
$this->logger->debug('debug', sprintf('code `%s` body `%s`', $exception->getResponse()->getStatusCode(), $exception->getResponse()->getBody()));
            throw new Jenkins('', 0, $exception);
        }
    }

}
