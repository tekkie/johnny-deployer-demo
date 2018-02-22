<?php

namespace App\Entity\Jira\Issue;

use JMS\Serializer\Annotation as Jms;

class Status
{
    /**
     * @var integer
     *
     * @Jms\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

}