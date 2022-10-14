<?php

namespace App\Controller;

use App\Entity\People;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/people')]
class PeopleController extends AbstractController
{
    #[Route('/', name: 'people')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(People::class);
        $allPeople = $repository->findAll();
        return $this->render('people/index.html.twig', [
            'allPeople' => $allPeople
        ]);
    }

    // #[Route('/{id<\d+>}', name: 'user')]
    // public function user(ManagerRegistry $doctrine, $id): Response
    // {
    //     $repository = $doctrine->getRepository(People::class);
    //     $person = $repository->find($id);

    //     if (!$person) {
    //         $this->addFlash(
    //             'error',
    //             "l'id $id n'exsiste pas !"
    //         );
    //         return $this->redirectToRoute('people');
    //     }
    //     return $this->render('people/user.html.twig', [
    //         'user' => $person
    //     ]);
    // }

    #[Route('/{id<\d+>}', name: 'user')]
    public function user(People $person): Response
    {
        if (!$person) {
            $this->addFlash(
                'error',
                "l'id n'exsiste pas !"
            );
            return $this->redirectToRoute('people');
        }
        return $this->render('people/user.html.twig', [
            'user' => $person
        ]);
    }

    #[Route('/add', name: 'add_people')]
    public function addPerson(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $person = new People();
        $person->setFirstname('Anass');
        $person->setName('BABA');
        $person->setAge('2');

        // $person2 = new People();
        // $person2->setFirstname('Omar');
        // $person2->setName('BABA');
        // $person2->setAge('27');

        //* ajouter l'opération d'insertion de person dans ma transaction
        $entityManager->persist($person);
        // $entityManager->persist($person2);
        //* Excuter la transaction #ToDo
        $entityManager->flush();

        return $this->render('people/person.html.twig', [
            'person' => $person,
        ]);
    }
}