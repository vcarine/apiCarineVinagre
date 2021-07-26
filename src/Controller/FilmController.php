<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Marque;
use App\Form\FilmType;
use App\Form\MarqueType;
use App\Repository\FilmRepository;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/films', name: 'film_', format: 'json')]
class FilmController extends AbstractController
{
    private $filmRepository;
    private $em;


    public function __construct(SerializerInterface $serializer,
                                FilmRepository $filmRepository,
                                EntityManagerInterface $em) /*//<= symfony\compenent\Serializer et App\Repo*/
    {
        $this->serializer = $serializer;
        $this->filmRepository = $filmRepository;
        $this->em = $em;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function getAll(): Response
    {
        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncode();

        $serializer = new Serializer([$normalizer], [$encoder]);

        //recupérér tous les voitures
        $cars = $this->filmRepository->findAll();
        return $this->json($cars);
    }

    #[Route('/{film}', name: 'one', methods: ['GET'])]
    public function getOne(Film $film)
    {

        return $this->json($film);

    }

    #[Route('/{film}', name: 'delete', methods: ['DELETE'])]
    public function delete(Film $film)
    {
        try {/*//essaie supp la marque*/
            $this->em->remove($film);
            $this->em->flush();
            return $this->json(['success' => true]); /*// si il y arrives , on retourne un success*/
        } catch (\Exception $e)/*//sinon*/ {
            return $this->json($e, 500);
        }
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function add(Request $request)   /*httpFoundation*/
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(FilmType::class, new Film());
        /* on récupère la requête*/
        $form->submit($data);

        if ($form->isSubmitted() and $form->isValid()) {
            $form = $form->getData();
            $this->em->persist($form);
            $this->em->flush();
            /* retourne un nouvel élément*/
            return $this->json($form, 201);

        } else {
            return $this->json($form->getErrors(true), 400);
        }
    }
        #[Route('/{film}', name: 'update', methods: ['PUT'])]
    public function update(Film $film, Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(FilmType::class, $film);

        $form->submit($data);

        if ($form->isSubmitted() and $form->isValid()) {
            $form = $form->getData();
            $this->em->flush();

            return $this->json($form, 202);
        } else {
            return $this->json($form->getErrors(true), 400);
        }

    }


}
