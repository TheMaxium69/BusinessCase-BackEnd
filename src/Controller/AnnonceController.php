<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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

    /**
     * @Route("/{id}", name="annonceFindOne", requirements={"id":"\d+"})
     */
    public function findOne(Annonce $annonce): Response
    {
        return $this->json($annonce, 201, [], [
            "groups"=> [
                "annonceFind"
            ]
        ]);
    }

    /**
     * @Route("/create", name="annonceCreate", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();

        $annonce = $serializer->deserialize($data, Annonce::class, 'json');

        $date = new \Datetime();
        $annonce->setCreatedAt($date);

        $user = $this->getUser();
        $annonce->setUser($user);

        dd($annonce);

        $manager->persist($annonce);
        $manager->flush();

        return $this->json($annonce, 201, [], [
            "groups"=> [
                "annonceFind"
            ]
        ]);
    }
}
