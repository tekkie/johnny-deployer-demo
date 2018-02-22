<?php

namespace App\Entity\Watson;

use JMS\Serializer\Annotation as Jms;

class Intent
{
    /**
     * @var string
     *
     * @Jms\Type("string")
     */
    private $intent;

    /**
     * @var float
     *
     * @Jms\Type("double")
     */
    private $confidence;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getIntent(): string
    {
        return $this->intent;
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

}
