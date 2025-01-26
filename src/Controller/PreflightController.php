<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PreflightController
{
    #[Route('/api/{any}', requirements: ['any' => '.*'], methods: ['OPTIONS'])]
    public function preflight(Request $request): JsonResponse
    {
        $origin = $request->headers->get('Origin');
        $allowedOrigin = 'https://rfess.fr'; // Origine autorisée

        if ($origin !== $allowedOrigin) {
            return new JsonResponse('Origin not allowed', 403);
        }

        return new JsonResponse(null, 204, [
            'Access-Control-Allow-Origin' => $allowedOrigin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            'Access-Control-Allow-Credentials' => 'true',
        ]);
    }
}

