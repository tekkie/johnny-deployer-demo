<?php

namespace App\Service;

class TaskTracking
{

    /**
     * @var \App\Repository\Jira
     */
    private $repository;

    public function __construct(\App\Repository\Jira $repository)
    {
        $this->repository = $repository;
    }

    public function readyForEnvironment(string $environment)
    {
        return $this->repository->search(
            'status IN (\'Ready for QA\')'
        );
    }
}
