<?php

namespace App\Entity\Jenkins;

use JMS\Serializer\Annotation as Jms;
use App\Entity\Jenkins\Build\Action;

class Build
{

    /**
     * @var Action[]
     *
     * @Jms\Type("array<App\Entity\Jenkins\Build\Action>")
     */
    private $actions;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $displayName;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $result;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $url;

    /**
     * @var integer
     *
     * @Jms\Type("integer")
     */
    private $timestamp;

    public function hasFailed()
    {
        if ('SUCCESS' !== $this->getResult()) {
            return false;
        }

        return true;
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp / 1000;
    }

}
