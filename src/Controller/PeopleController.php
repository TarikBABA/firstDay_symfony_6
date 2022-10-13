<?php

namespace App\Controller;

use App\Entity\People;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeopleController extends AbstractController
{
    #[Route('/people/add', name: 'app_people')]
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

        return $this->render('people/detail.html.twig', [
            'person' => $person,
        ]);
    }
}