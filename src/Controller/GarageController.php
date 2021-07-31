<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Repository\GarageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/garage")
 */
class GarageController extends AbstractController
{
    /**
     * @Route("s", name="garageFindAll")
     */
    public function findAll(GarageRepository $garageRepository): Response
    {
        $garages = $garageRepository->findAll();

        return $this->json($garages, 201, [], [
            "groups"=> [
                "garagesFind"
            ]
        ]);
    }

    /**
     * @Route("/{id}", name="garageFindAll")
     */
    public function findOne(Garage $garage): Response
    {

        return $this->json($garage, 201, [], [
            "groups"=> [
                "garagesFind"
            ]
        ]);
    }
}
