<?php

namespace App\Controller;

use App\Entity\People;

use App\Repository\PeopleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/people')]
class PeopleController extends AbstractController
{
    //? Show All people 1 page
    #[Route('/', name: 'people')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(People::class);
        $people = $repository->findAll();
        return $this->render('people/index.html.twig', [
            'isPaginated' => false,
            'allPeople' => $people
        ]);
    }

    // #[Route("/dql/{ageMin}/{ageMax}", name: 'dql')]
    // public function peopleByAge($ageMin, $ageMax, PeopleRepository $repository)
    // {
    //     // /** @var PeopleRepository $respository */
    //     $people = $repository->findPeopleByAgeInterval($ageMin, $ageMax);
    //     return $this->render('people/index.html.twig', [
    //         'isPaginated' => false,
    //         'allPeople' => $people
    //     ]);
    // }


    // !-------------------------------------------------------------------------------
    // #[Route("/dql/{ageMin}/{ageMax}", name: 'dql')]
    // public function peopleByAge(ManagerRegistry $doctrine, $ageMin, $ageMax)
    // {
    //     /** @var PeopleRepository $respository */

    //     $repository = $doctrine->getRepository(People::class);
    //     // dd($repository);

    //     $people = $repository->findPeopleByAgeInterval($ageMin, $ageMax);
    //     return $this->render('people/index.html.twig', [
    //         'isPaginated' => false,
    //         'allPeople' => $people
    //     ]);
    // }

    // !--------------------------------------------------------------------------------

    #[Route('/test', name: 'people.test')]
    public function indexByName(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(People::class);
        $people = $repository->findBy(['name' => 'baba'], ['age' => 'DESC']);
        return $this->render('people/index.html.twig', [
            'allPeople' => $people,
            'isPaginated' => false
        ]);
    }

    // ? Show All with Pagination
    #[Route('/pages/{page?1}/{nbr?12}', name: 'people.pagination')]
    public function indexPagination(ManagerRegistry $doctrine, $nbr, $page): Response
    {

        $repository = $doctrine->getRepository(People::class);
        $nbPeople = $repository->count([]);
        $nbPages = ceil($nbPeople / $nbr);
        $allPeople = $repository->findBy([], [], $nbr, ($page - 1) * $nbr);
        return $this->render('people/index.html.twig', [
            'allPeople' => $allPeople,
            'isPaginated' => true,
            'nbPages' => $nbPages,
            'nbr' => $nbr,
            'page' => $page
        ]);
    }

    #[Route('/{id<\d+>}', name: 'details.user')]
    public function user(People $person = null): Response
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




    #[Route('/delete/{id}', name: 'delete.people')]
    public function deletePerson(People $person = null, ManagerRegistry $doctrine): RedirectResponse
    {

        if ($person) {
            $manager = $doctrine->getManager();
            $manager->remove($person);
            $manager->flush();
            $this->addFlash(
                'success',
                'la personne a été supprimer avec succès'
            );
        } else {
            $this->addFlash(
                'error',
                'person not exist'
            );
        }
        return $this->redirectToRoute('people.pagination');
    }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: 'updatePerson')]
    public function updatePerson(People $person = null, $name, $firstname, $age, ManagerRegistry $doctrine)
    {
        if ($person) {
            $person->setName($name);
            $person->setFirstname($firstname);
            $person->setAge($age);
            $manager = $doctrine->getManager();
            $manager->persist($person);
            $manager->flush($person);
            $this->addFlash(
                'success',
                'mise à jour avec succès'
            );
        } else {
            $this->addFlash(
                'error',
                'not exist'
            );
        }
        return $this->redirectToRoute('people.pagination');
    }
}