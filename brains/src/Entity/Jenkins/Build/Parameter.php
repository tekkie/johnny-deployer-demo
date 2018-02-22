<?php

namespace App\Entity\Jenkins\Build;

use JMS\Serializer\Annotation as Jms;

class Parameter
{

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
    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

}
