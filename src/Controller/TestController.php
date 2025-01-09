<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/api/cors-test', name: 'corsTest', methods: ['GET'])]
    public function corsTest() {
        return new JsonResponse('Cors test', 200, ['Access-Control-Allow-Origin' => '*']);
    }
}