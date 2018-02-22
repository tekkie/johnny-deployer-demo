<?php

namespace App\Controller;

use App\Entity\Jenkins\Build\Parameter;
use App\Entity\Watson\Intent;
use App\Service\Ask;
use App\Service\TaskTracking;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConversationalController extends Controller
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TaskTracking
     */
    private $taskTracking;

    public function index(Request $request, LoggerInterface $logger)
    {
        $responseText = '';
        $this->logger = $logger;

        // decode what we got from JohnnyTheBot
        $apiRequest = $this->get('jms_serializer')->deserialize(
            $request->getContent(),
            'App\Entity\Request',
            'json'
        );

        if (is_null($apiRequest->message)) {
            return new Response('');
        }

        /** @var \App\Entity\Watson\Response $apiResponse */
        $apiResponse = $this->get(Ask::class)->ask($apiRequest->message);
        $this->logger->debug(sprintf('Watson says: %s', $this->get('jms_serializer')->serialize($apiResponse, 'json')));

        // append what Watson said to what we'll give back to botmaster
        $responseText .= implode('', $apiResponse->getOutput()->text);

        if (0 == count($apiResponse->getIntents())) {
            $this->logger->debug(sprintf(
                'No intent for me this time. Watson said `%s`',
                $responseText
            ));
            return new Response($responseText);
        }

        foreach ($apiResponse->getIntents() as $intent) {
            /** @var Intent $intent */
            switch ($intent->getIntent()) {
                case '1_tickets_ready_for_environment':
                    $responseText .= $this->readyForEnvironment(
                        $apiResponse->getEnvironment()
                    );
                    break;
                case '2_Prepare_RC_and_Deploy':
                    if (is_null($apiResponse->getEnvironment()) || is_null($apiResponse->getComponent())) {
                        $responseText .= sprintf(
                            'I think you want to build and deploy, but I am unsure what. Environment = `%s`. Component = `%s`.',
                            $apiResponse->getEnvironment(),
                            $apiResponse->getComponent()
                        );
                        break;
                    }
                    $this->buildAndDeploy(
                        $apiResponse->getEnvironment(),
                        $apiResponse->getComponent()
                    );
                    break;
                case '3_component_info':
                    $responseText .= $this->lastComponentActionOnEnvironment(
                        $apiResponse->getComponent(),
                        $apiResponse->getAction(),
                        $apiResponse->getEnvironment()
                    );
                    break;
                default:
                    break;
            }
        }

        return new Response($responseText);
    }

    private function readyForEnvironment(string $environment): string
    {
        /** @var \App\Service\TaskTracking $taskTracking */
        $taskTracking = $this->get(TaskTracking::class);

        $issues = $taskTracking->readyForEnvironment($environment);

        if (0 == $issues->getTotal()) {
            return 'I found no issues in the testing queue, please try again later';
        }

        $response = sprintf('Found %d item(s) ready for testing:', $issues->getTotal());
        foreach ($issues->getIssues() as $issue) {
            $response .= "\n" . sprintf(
                '* [%s/browse/%s](%s) %s ' . "\n",
                str_replace('/rest', '', getenv('JIRA_ENDPOINT_PUBLIC')),
                $issue->getKey(),
                $issue->getKey(),
                $issue->getFields()->getSummary()
            );
        }

        return $response;
    }

    private function buildAndDeploy(string $environment, string $component): void
    {
        $executeRequest = (new \App\Entity\Jenkins\Build\Request())
            ->addParameter(new Parameter('ENVIRONMENT', $environment))
            ->addParameter(new Parameter('COMPONENT', $component))
        ;

        /** @var \App\Service\Execute $executor */
        $executor = $this->get('executor.job');
        $executor->executeWithParameters(
            '2_Prepare_RC_and_Deploy',
            $executeRequest
        );
    }

    private function lastComponentActionOnEnvironment(
            ?string $component,
            ?string $action,
            ?string $environment
    )
    {
        if (is_null($component) || is_null($environment)) {
            return 'I did not get the complete information, need component and environment to proceed';
        }
        $this->logger->debug(sprintf('Component: %s, Environment: %s %s', $component, $environment, $action));

        /** @var \App\Service\Execute $execute */
        $execute = $this->get('executor.job');
        return $execute->history($component, $environment);
    }

}
