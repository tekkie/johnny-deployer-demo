<?php

namespace App\Entity\Watson;

use JMS\Serializer\Annotation as Jms;

class Input
{
    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

}
