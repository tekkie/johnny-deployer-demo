<?php

namespace App\Entity\Jira\Response;

use App\Entity\Jira\Issue;
use JMS\Serializer\Annotation as Jms;

class Issues
{
    /**
     * @var Issue[]
     *
     * @Jms\Type("array<App\Entity\Jira\Issue>")
     */
    private $issues = [];

    /**
     * @var integer
     *
     * @Jms\Type("integer")
     */
    protected $total = 0;

    /**
     * @return Issue[]
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * @param Issue[] $issues
     * @return Issues
     */
    public function setIssues(array $issues): Issues
    {
        $this->issues = $issues;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return Issues
     */
    public function setTotal(int $total): Issues
    {
        $this->total = $total;
        return $this;
    }

}
