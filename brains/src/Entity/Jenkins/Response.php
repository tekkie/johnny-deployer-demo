<?php

namespace App\Entity\Jenkins;

use JMS\Serializer\Annotation as Jms;
use App\Entity\Jenkins\Build;

class Response
{

    /**
     * @var Build[]
     *
     * @Jms\Type("array<App\Entity\Jenkins\Build>")
     */
    private $builds;

    /**
     * @return Build[]
     */
    public function getBuilds(): array
    {
        return $this->builds;
    }

}
