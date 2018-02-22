<?php

namespace App\Entity;

use JMS\Serializer\Annotation as Jms;

class Request
{

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    public $message;

}
