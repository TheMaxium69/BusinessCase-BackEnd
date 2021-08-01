<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("s", name="annonceFindAll")
     */
    public function findAll(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findAll();

        return $this->json($annonces, 201, [], [
            "groups"=> [
                "annonceFind"
            ]
        ]);
    }
}
