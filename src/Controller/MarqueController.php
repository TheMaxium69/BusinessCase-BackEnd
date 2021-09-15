<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/marque")
 */
class MarqueController extends AbstractController
{
    /**
     * @Route("", name="marqueIndex")
     */
    public function index(): Response
    {
        return $this->redirectToRoute("marqueFindAll");
    }

    /**
     * @Route("s", name="marqueFindAll")
     */
    public function findAll(MarqueRepository $marqueRepository): Response
    {
        $marque = $marqueRepository->findAll();

        return $this->json($marque, 201, [], [
            "groups"=> [
                "marqueFind"
            ]
        ]);
    }

    /**
     * @Route("/{id}", name="marqueFindOne", requirements={"id":"\d+"})
     */
    public function findOne(Marque $marque): Response
    {
        return $this->json($marque, 201, [], [
            "groups"=> [
                "marqueFind"
            ]
        ]);
    }
}
