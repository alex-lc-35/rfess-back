<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class PlaceController extends AbstractController
{
    #[Route('/api/places/{id}', name: 'api_places_show', methods: ['GET'])]
    public function show(int $id, PlaceRepository $placeRepository): JsonResponse
    {
        $place = $placeRepository->find($id);

        if (!$place) {
            return $this->json(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($place, Response::HTTP_OK);
    }

    #[Route('/api/places', name: 'api_places_index', methods: ['GET'])]
    public function index(Request $request, PlaceRepository $placeRepository): JsonResponse
    {
        $city = $request->query->get('city');
        $categories = $request->query->get('categories'); // format attendu : "cat1,cat2"

        $queryBuilder = $placeRepository->createQueryBuilder('p');

        if ($city) {
            $queryBuilder->andWhere('p.city = :city')
                ->setParameter('city', $city);
        }

        if ($categories) {
            $categoriesArray = explode(',', $categories);
            $queryBuilder->join('p.categories', 'c')
                ->andWhere('c.name IN (:categories)')
                ->setParameter('categories', $categoriesArray);
        }

        $places = $queryBuilder->getQuery()->getResult();

        return $this->json($places, Response::HTTP_OK);
    }

    #[Route('/api/places', name: 'api_places_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $place = new Place();
            $place->setName($data['name']);
            $place->setCity($data['city']);
            $place->setAddress($data['address']);
            $place->setLatitude($data['latitude']);
            $place->setLongitude($data['longitude']);
            $place->setDescription($data['description']);
            // Ajouter les catégories ici si nécessaire

            $entityManager->persist($place);
            $entityManager->flush();

            return $this->json($place, Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    #[Route('/api/places/{id}', name: 'api_places_update', methods: ['PUT', 'PATCH'])]
    public function update(
        int $id,
        Request $request,
        PlaceRepository $placeRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $place = $placeRepository->find($id);

        if (!$place) {
            return $this->json(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Mettre à jour les champs si les données sont présentes
        if (isset($data['name'])) {
            $place->setName($data['name']);
        }
        if (isset($data['city'])) {
            $place->setCity($data['city']);
        }
        if (isset($data['address'])) {
            $place->setAddress($data['address']);
        }
        if (isset($data['longitude'])) {
            $place->setLongitude($data['longitude']);
        }
        if (isset($data['latitude'])) {
            $place->setLatitude($data['latitude']);
        }
        if (isset($data['description'])) {
            $place->setDescription($data['description']);
        }

        // Sauvegarde
        $entityManager->flush();

        return $this->json($place, Response::HTTP_OK);
    }
}
