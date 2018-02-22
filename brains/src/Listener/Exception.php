<?php

namespace App\Listener;

use App\Exception\Rest\Watson;
use App\Exception\Rest\Jenkins;
use App\Rest\Jira;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use GuzzleHttp\Exception\RequestException;

class Exception
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        $this->logger->debug(sprintf(
            'Catching a `%s` with message `%s`, full trace `%s`',
            get_class($exception),
            $exception->getMessage(),
            $exception->getTraceAsString()
        ));

        switch (true) {
            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            case $exception instanceof HttpExceptionInterface:
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
                break;
            case $exception instanceof RequestException:
                $response->setContent(
                    'I had trouble grabbing that information for you.'
                    . $exception->getMessage()
                );
                break;
            case $exception instanceof Watson:
                $response->setContent(
                    'I had trouble finding that out for you.'
                    . $exception->getMessage()
                );
                break;
            case $exception instanceof Jenkins:
            case $exception instanceof Jira:
                $response->setContent(
                    'I had trouble executing that out for you.'
                    . $exception->getMessage()
                    . $exception->getTraceAsString()
                );
                break;
            default:
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                break;
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
