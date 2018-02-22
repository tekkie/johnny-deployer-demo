<?php

namespace App\Entity\Watson;

use JMS\Serializer\Annotation as Jms;

class Request
{

    /**
     * @var Input
     *
     * @Jms\Type("App\Entity\Watson\Input")
     */
    private $input;

    public function __construct(string $text)
    {
        $this->input = new Input($text);
    }

    /**
     * @return Input
     */
    public function getInput(): Input
    {
        return $this->input;
    }

}
