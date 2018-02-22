<?php

namespace App\Entity\Jira\Issue;

use App\Entity\Jira\Issue\Status;

use JMS\Serializer\Annotation as Jms;

class Fields
{
    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $summary;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $description;

    /**
     * @var Status
     *
     * @Jms\Type("App\Entity\Jira\Issue\Status")
     */
    private $status;

    /**
     * @var string[]
     *
     * @Jms\Type("array<string>")
     */
    private $labels;

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

}
