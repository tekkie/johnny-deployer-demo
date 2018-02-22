<?php

namespace App\Entity\Watson;

use JMS\Serializer\Annotation as Jms;

class Response
{

    /**
     * @var array
     *
     * @Jms\Type("array<App\Entity\Watson\Intent>")
     */
    private $intents;

    /**
     * @var array
     *
     * @Jms\Type("array<App\Entity\Watson\Entity>")
     */
    private $entities;

    /**
     * @var array
     *
     * @Jms\Type("App\Entity\Watson\Input")
     */
    private $input;

    /**
     * @var Output
     *
     * @Jms\Type("App\Entity\Watson\Output")
     */
    private $output;

    public function getEnvironment(): ?string
    {
        return $this->extractEntityByName('ENVIRONMENT');
    }

    public function getAction(): ?string
    {
        return $this->extractEntityByName('info_type');
    }

    public function getComponent(): ?string
    {
        $component = $this->extractEntityByName('systems');

        if (in_array($component, ['cms', 'api'])) {
            $component = strtoupper($component);
        }

        if ('web' == $component) {
            $component = 'frontend';
        }

        return $component;
    }

    public function extractEntityByName(string $name): ?string
    {
        foreach ($this->getEntities() as $entity) {
            /** @var Entity $entity */
            if (strtolower($name) !== strtolower($entity->getEntity())) {
                continue;
            }

            return $entity->getValue();
        }

        return null;
    }

    /*---------------------------- accessors --------------------------------*/
    /*-----------------------------------------------------------------------*/

    /**
     * @return array
     */
    public function getIntents(): array
    {
        return $this->intents;
    }

    /**
     * @param array $intents
     */
    public function setIntents(array $intents)
    {
        $this->intents = $intents;
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     */
    public function setEntities(array $entities)
    {
        $this->entities = $entities;
    }

    /**
     * @return array
     */
    public function getInput(): array
    {
        return $this->input;
    }

    /**
     * @param array $input
     */
    public function setInput(array $input)
    {
        $this->input = $input;
    }

    /**
     * @return Output
     */
    public function getOutput(): Output
    {
        return $this->output;
    }

    /**
     * @param Output $output
     */
    public function setOutput(Output $output)
    {
        $this->output = $output;
    }

}
