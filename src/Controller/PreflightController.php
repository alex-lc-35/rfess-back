<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PreflightController
{
    #[Route('/api/{any}', requirements: ['any' => '.*'], methods: ['OPTIONS'])]
    public function preflight(): JsonResponse
    {
        return new JsonResponse(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
        ]);
    }
}
