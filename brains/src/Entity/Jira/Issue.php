<?php

namespace App\Entity\Jira;

use App\Entity\Jira\Issue\Status;
use App\Entity\Jira\Issue\Fields;

use JMS\Serializer\Annotation as Jms;

class Issue
{
    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $key;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $id;

    /**
     * @var Fields
     *
     * @Jms\Type("App\Entity\Jira\Issue\Fields")
     */
    private $fields;

    /**
     * @var Status
     *
     * @Jms\Type("App\Entity\Jira\Issue\Status")
     */
    private $status;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $self;

    /**
     * @return mixed
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return Issue
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Issue
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Fields
     */
    public function getFields(): Fields
    {
        return $this->fields;
    }

    /**
     * @param Fields $fields
     * @return Issue
     */
    public function setFields(Fields $fields): Issue
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return Issue
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelf(): string
    {
        return $this->self;
    }

    /**
     * @param string $self
     * @return Issue
     */
    public function setSelf(string $self): Issue
    {
        $this->self = $self;
        return $this;
    }

}
