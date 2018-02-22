<?php

namespace App\Entity\Jenkins\Build;

class Request
{

    /**
     * @var Parameter[]
     */
    private $parameters;

    public function addParameter(Parameter $parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

}
