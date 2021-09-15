<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Repository\CarburantRepository;
use App\Repository\GarageRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModelRepository;
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
     * @Route("", name="annonceIndex")
     */
    public function index(): Response
    {
        return $this->redirectToRoute("annonceFindAll");
    }

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
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, MarqueRepository $repoMarque, ModelRepository $repoModel, CarburantRepository $repoCarburant, GarageRepository $repoGarage): Response
    {
        $data = $request->getContent();

        $marque = $repoMarque->findOneBy(["id" => $request->toArray("marque_id")]);
        $model = $repoModel->findOneBy(["id" => $request->toArray("model_id")]);
        $carburant = $repoCarburant->findOneBy(["id" => $request->toArray("carburant_id")]);
        $garage = $repoGarage->findOneBy(["id" => $request->toArray("garage_id")]);

        $annonce = $serializer->deserialize($data, Annonce::class, 'json');

        $annonce->setMarque($marque)->setCarburant($carburant)->setModel($model)->setGarage($garage);

        if ($garage->getUser() == $this->getUser()){

            $date = new \Datetime();
            $annonce->setCreatedAt($date);

            $user = $this->getUser();
            $annonce->setUser($user);

            $manager->persist($annonce);
            $manager->flush();

            return $this->json($annonce, 201, [], [
                "groups"=> [
                    "annonceFind"
                ]
            ]);
        }
        return $this->json(["ERREUR" => "GARAGE"], 201);
    }

    /**
     * @Route("/delete/{id}", name="annonceDelete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Annonce $annonce, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $userAnnonce = $annonce->getUser();

        if ($userAnnonce == $user){
            $manager->remove($annonce);
            $manager->flush();
            $message = "REMOVE OK";
        }else{
            $message = "NOT REMOVE";
        }

        return $this->json($message, 201);
    }

    /**
     * @Route("/edit/{id}", name="annonceEdit", methods={"PUT"}, requirements={"id":"\d+"})
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, MarqueRepository $repoMarque, ModelRepository $repoModel, CarburantRepository $repoCarburant, GarageRepository $repoGarage): Response
    {
        $data = $request->getContent();

        $marque = $repoMarque->findOneBy(["id" => $request->toArray("marque_id")]);
        $model = $repoModel->findOneBy(["id" => $request->toArray("model_id")]);
        $carburant = $repoCarburant->findOneBy(["id" => $request->toArray("carburant_id")]);
        $garage = $repoGarage->findOneBy(["id" => $request->toArray("garage_id")]);

        $annonceEdit = $serializer->deserialize($data, Annonce::class, 'json');

        $user = $this->getUser();
        $userAnnonce = $annonce->getUser();

        if ($userAnnonce == $user){

            $annonce->setTitle($annonceEdit->getTitle());
            $annonce->setDescription($annonceEdit->getDescription());
            $annonce->setYear($annonceEdit->getYear());
            $annonce->setKilometrage($annonceEdit->getKilometrage());
            $annonce->setPrice($annonceEdit->getPrice());


            $annonce->setMarque($marque);
            $annonce->setCarburant($carburant);
            $annonce->setModel($model);
            if ($garage->getUser() == $this->getUser()){
                $annonce->setGarage($garage);
            }

            $manager->persist($annonce);
            $manager->flush();
            return $this->json($annonce, 201, [], [
                "groups"=> [
                    "annonceFind"
                ]
            ]);
        }else{
            $message = "NOT EDIT";
            return $this->json($message, 201);
        }
    }
}
