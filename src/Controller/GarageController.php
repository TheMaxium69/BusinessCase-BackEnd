<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Repository\GarageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/garage")
 */
class GarageController extends AbstractController
{
    /**
     * @Route("", name="annonceIndex")
     */
    public function index(): Response
    {
        return $this->json([
            "FindAll" => "/api/garages",
            "FindOne" => "/api/garage/{id}",
            "Create" => "/api/garage/create",
            "Edit" => "/api/garage/edit"
        ]);
    }

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
     * @Route("/{id}", name="garageFindOne", requirements={"id":"\d+"})
     */
    public function findOne(Garage $garage): Response
    {
        return $this->json($garage, 201, [], [
            "groups"=> [
                "garagesFind"
            ]
        ]);
    }

    /**
     * @Route("/create", name="garageCreate", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();

        $garage = $serializer->deserialize($data, Garage::class, 'json');

        $date = new \Datetime();
        $garage->setCreatedAt($date);

        $user = $this->getUser();
        $garage->setUser($user);

        $manager->persist($garage);
        $manager->flush();

        return $this->json($garage, 201, [], [
            "groups"=> [
                "garagesFind"
            ]
        ]);
    }

    /**
     * @Route("/delete/{id}", name="garageDelete", requirements={"id":"\d+"})
     */
    public function delete(Garage $garage, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $userGarage = $garage->getUser();

        if ($userGarage == $user){
            $manager->remove($garage);
            $manager->flush();
            $message = "REMOVE OK";
        }else{
            $message = "NOT REMOVE";
        }

        return $this->json($message, 201);
    }

    /**
     * @Route("/edit/{id}", name="garageEdit", methods={"PUT"}, requirements={"id":"\d+"})
     */
    public function edit(Garage $garage, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $garageEdit = $serializer->deserialize($data, Garage::class, 'json');

        $user = $this->getUser();
        $userGarage = $garage->getUser();

        if ($userGarage == $user){

            $garage->setName($garageEdit->getName());
            $garage->setAdresse($garageEdit->getAdresse());
            $garage->setPhoneNumber($garageEdit->getPhoneNumber());

            $manager->persist($garage);
            $manager->flush();
            return $this->json($garage, 201, [], [
                "groups"=> [
                    "garagesFind"
                ]
            ]);
        }else{
            $message = "NOT EDIT";
            return $this->json($message, 201);
        }
    }
}
