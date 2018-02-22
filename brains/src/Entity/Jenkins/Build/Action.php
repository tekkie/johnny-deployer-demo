<?php

namespace App\Entity\Jenkins\Build;

use JMS\Serializer\Annotation as Jms;
use App\Entity\Jenkins\Build\Parameter;

class Action
{

    /**
     * @var Parameter[]
     *
     * @Jms\Type("array<App\Entity\Jenkins\Build\Parameter>")
     */
    private $parameters;

    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $_class;

    public function matches(string $component, string $environment)
    {
        $matchedComponent = false;
        $matchedEnvironment = false;

        foreach ($this->getParameters() as $parameter) {
            if (strtolower('ENVIRONMENT') == strtolower($parameter->getName())
                    && strtolower($environment) == strtolower($parameter->getValue())
            ) {
                $matchedEnvironment = true;
            }

            if (strtolower('COMPONENT') == strtolower($parameter->getName())
                    && strtolower($component) == strtolower($parameter->getValue())
        ) {
                $matchedComponent = true;
            }
        }

        if (! $matchedComponent || ! $matchedEnvironment) {
            return false;
        }

        return true;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getClass(): ?string
    {
        return $this->_class;
    }

}
