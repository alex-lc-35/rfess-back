<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreflightController
{
    #[Route('/api/{any}', requirements: ['any' => '.*'], methods: ['OPTIONS', 'GET', 'POST', 'PUT', 'DELETE'])]
    public function preflight(Request $request): JsonResponse
    {
        $allowedOrigins = ['https://rfess.fr', 'http://localhost:3000']; // Ajoute plusieurs origines autorisées
        $origin = $request->headers->get('Origin');

        if (!in_array($origin, $allowedOrigins)) {
            return new JsonResponse('Origin not allowed', Response::HTTP_FORBIDDEN, [
                'Access-Control-Allow-Origin' => implode(',', $allowedOrigins),
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        }

        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        // Gérer les requêtes pré-volées OPTIONS (retourne une réponse vide avec les bons en-têtes)
        if ($request->isMethod('OPTIONS')) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT, $headers);
        }

        return new JsonResponse(['message' => 'CORS headers set successfully'], Response::HTTP_OK, $headers);
    }
}
