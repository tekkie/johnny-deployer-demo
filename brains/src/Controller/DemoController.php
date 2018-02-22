<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DemoController extends Controller
{

    public function demo()
    {
        return new JsonResponse([
            'status' => 'OK',
            'CONVERSATION_ENDPOINT' => getenv('CONVERSATION_ENDPOINT')
        ]);
    }

}
