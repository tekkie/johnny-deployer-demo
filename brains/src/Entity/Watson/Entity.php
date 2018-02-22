<?php

namespace App\Entity\Watson;

use JMS\Serializer\Annotation as Jms;

class Entity
{
    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $entity;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $value;

    /**
     * @var float
     *
     * @Jms\Type("double")
     */
    private $confidence;

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

}
