<?php

namespace App\Controller;

use App\Entity\Carburant;
use App\Repository\CarburantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/carburant")
 */
class CarburantController extends AbstractController
{
    /**
     * @Route("", name="carburantIndex")
     */
    public function index(): Response
    {
        return $this->redirectToRoute("carburantFindAll");
    }

    /**
     * @Route("s", name="carburantFindAll")
     */
    public function findAll(CarburantRepository $carburantRepository): Response
    {
        $carburant = $carburantRepository->findAll();

        return $this->json($carburant, 201, [], [
            "groups"=> [
                "carburantFind"
            ]
        ]);
    }

    /**
     * @Route("/{id}", name="carburantFindOne", requirements={"id":"\d+"})
     */
    public function findOne(Carburant $carburant): Response
    {
        return $this->json($carburant, 201, [], [
            "groups"=> [
                "carburantFind"
            ]
        ]);
    }

    /**
     * @Route("/create", name="carburantCreate", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $userRole = $user->getRoles();
        if ($userRole['0'] == "ROLE_ADMIN"){
            $data = $request->getContent();

            $carburant = $serializer->deserialize($data, Carburant::class, 'json');

            $manager->persist($carburant);
            $manager->flush();

            return $this->json($carburant, 201, [], [
                "groups"=> [
                    "carburantFind"
                ]
            ]);
        }
    }

    /**
     * @Route("/delete/{id}", name="carburantDelete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Carburant $carburant, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $userRole = $user->getRoles();
        if ($userRole['0'] == "ROLE_ADMIN"){
            $manager->remove($carburant);
            $manager->flush();
            $message = "REMOVE OK";
        }else{
            $message = "NOT REMOVE";
        }

        return $this->json($message, 201);
    }

    /**
     * @Route("/edit/{id}", name="carburantEdit", methods={"PUT"}, requirements={"id":"\d+"})
     */
    public function edit(Carburant $carburant, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $carburantEdit = $serializer->deserialize($data, Carburant::class, 'json');

        $user = $this->getUser();
        $userRole = $user->getRoles();

        if ($userRole['0'] == "ROLE_ADMIN"){

            $carburant->setName($carburantEdit->getName());

            $manager->persist($carburant);
            $manager->flush();
            return $this->json($carburant, 201, [], [
                "groups"=> [
                    "carburantFind"
                ]
            ]);
        }else{
            $message = "NOT EDIT";
            return $this->json($message, 201);
        }
    }
}
