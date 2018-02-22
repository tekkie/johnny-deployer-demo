<?php

namespace App\Entity\Watson;

use JMS\Serializer\Annotation as Jms;

class Output
{
    /**
     * @var string[]
     *
     * @Jms\Type("array<string>")
     */
    public $text;
}
