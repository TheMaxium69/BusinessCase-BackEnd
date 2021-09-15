<?php

namespace App\Controller;

use App\Entity\Carburant;
use App\Entity\Marque;
use App\Entity\Model;
use App\Repository\CarburantRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/model")
 */
class ModelController extends AbstractController
{
    /**
     * @Route("", name="modelIndex")
     */
    public function index(): Response
    {
        return $this->redirectToRoute("modelFindAll");
    }

    /**
     * @Route("s", name="modelFindAll")
     */
    public function findAll(ModelRepository $modelRepository): Response
    {
        $model = $modelRepository->findAll();

        return $this->json($model, 201, [], [
            "groups"=> [
                "modelFind"
            ]
        ]);
    }

    /**
     * @Route("/{id}", name="modelFindOne", requirements={"id":"\d+"})
     */
    public function findOne(Model $model): Response
    {
        return $this->json($model, 201, [], [
            "groups"=> [
                "modelFind"
            ]
        ]);
    }

    /**
     * @Route("/create", name="modelCreate", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, MarqueRepository $marqueRepository): Response
    {

            $data = $request->getContent();

            $marque = $marqueRepository->findOneBy(["id" => $request->toArray("marque_id")]);

            $model = $serializer->deserialize($data, Model::class, 'json');

            $model->setMarque($marque);

            $manager->persist($model);
            $manager->flush();

            return $this->json($model, 201, [], [
                "groups"=> [
                    "modelFind"
                ]
            ]);

    }

    /**
     * @Route("/delete/{id}", name="modelDelete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Model $model, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $userRole = $user->getRoles();
        if ($userRole['0'] == "ROLE_ADMIN"){
            $manager->remove($model);
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
    public function edit(Model $model, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, MarqueRepository $marqueRepository): Response
    {
        $data = $request->getContent();

        $marque = $marqueRepository->findOneBy(["id" => $request->toArray("marque_id")]);

        $modelEdit = $serializer->deserialize($data, Model::class, 'json');

        $user = $this->getUser();
        $userRole = $user->getRoles();

        if ($userRole['0'] == "ROLE_ADMIN"){

            $model->setName($modelEdit->getName());
            $model->setMarque($marque);

            $manager->persist($model);
            $manager->flush();
            return $this->json($model, 201, [], [
                "groups"=> [
                    "modelFind"
                ]
            ]);
        }else{
            $message = "NOT EDIT";
            return $this->json($message, 201);
        }
    }
}
