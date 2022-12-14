<?php

namespace App\Controller;

use App\Entity\People;
use App\Event\AddPersonEvent;
use App\Event\ListAllEvent;
use App\Form\PeopleType;
use App\Service\Helpers;
use Psr\Log\LoggerInterface;
use App\Service\MailerService;
use App\Service\PdfService;
use App\Service\UploaderService;

// *use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//? use App\Repository\PeopleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/people'), IsGranted('ROLE_USER')]
class PeopleController extends AbstractController
{

    public function __construct(
        private LoggerInterface $logger,
        private Helpers $helper,
        // private MailerService $mailer
        private EventDispatcherInterface $dispatcher
    ) {
    }

    //? Show All people 1 page
    #[Route('/', name: 'people'), IsGranted("ROLE_USER")]

    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(People::class);
        $people = $repository->findAll();
        $listAllPersonEvent = new ListAllEvent(count($people));
        $this->dispatcher->dispatch($listAllPersonEvent, ListAllEvent::LIST_PEOPLE_EVENT);
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
        //// $helpers = new Helpers();
        // echo $this->helper->sayHello();

        return $this->render('people/user.html.twig', [
            'user' => $person
        ]);
    }

    #[Route("/pdf/{id}", name: "pdf_person")]
    public function generatePdf(People $person = null, PdfService $pdf)
    {
        $html = $this->render('people/person.html.twig', [
            'person' => $person,
        ]);
        $pdf->showPdf($html);
    }


    // #[Route('/add', name: 'add_people')]
    // public function addPerson(ManagerRegistry $doctrine): Response
    // {
    //     $entityManager = $doctrine->getManager();
    //     $person = new People();
    //     $person->setFirstname('Anass');
    //     $person->setName('BABA');
    //     $person->setAge('2');

    //     // $person2 = new People();
    //     // $person2->setFirstname('Omar');
    //     // $person2->setName('BABA');
    //     // $person2->setAge('27');

    //     //* ajouter l'op??ration d'insertion de person dans ma transaction
    //     $entityManager->persist($person);
    //     // $entityManager->persist($person2);
    //     //* Excuter la transaction #ToDo
    //     $entityManager->flush();

    //     return $this->render('people/person.html.twig', [
    //         'person' => $person,
    //     ]);
    // }

    #[Route('/edit/{id?0}', name: 'edit_people')]
    public function addPerson(
        People $person = null,
        ManagerRegistry $doctrine,
        Request $request,
        UploaderService $uploaderService,
        MailerService $mailer
    ): Response {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $new = false;

        if (!$person) {
            $new = true;
            $person = new People(); // $person est l'image de notre form
        }

        $form = $this->createForm(PeopleType::class, $person);
        $form->remove('createdAt');
        $form->remove('updateAt');

        // dump($request);
        $form->handleRequest($request); // mon form va trait?? la requ??te

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($person);
            // dd($form->getData($person)); 
            ////* recup??ration des donn??es de mani??re classique sans qu'elle soit associ?? ?? un objet

            $imageFile = $form->get('photo')->getData();
            if ($imageFile) {
                $directory = $this->getParameter('person_directory');


                $person->setImage($uploaderService->uploadFile($imageFile, $directory));
            }
            if ($new) {
                $message = "a ??t?? ajout?? avec succ??s";
                $person->setCreatedBy($this->getUser());
            } else {
                $message = "a ??t?? edit?? avec succ??s";
            }

            $manager = $doctrine->getManager();
            $manager->persist($person);
            $manager->flush();

            if ($new) {
                $addPersonEvent = new AddPersonEvent($person); // Event Created
                // ? here dispatch
                $this->dispatcher->dispatch($addPersonEvent, AddPersonEvent::ADD_PERSON_EVENT);
            }

            $this->addFlash('success', $person->getName() . ' ' . $message);

            return $this->redirectToRoute('people');
        } else {
            return $this->render('people/add-person.html.twig', [
                'form' => $form->createView()
            ]);
        }
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




    #[Route('/delete/{id}', name: 'delete.people'), IsGranted('ROLE_ADMIN')]
    public function deletePerson(People $person = null, ManagerRegistry $doctrine): RedirectResponse
    {

        if ($person) {
            $manager = $doctrine->getManager();
            $manager->remove($person);
            $manager->flush();
            $this->addFlash(
                'success',
                'la personne a ??t?? supprimer avec succ??s'
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
                'mise ?? jour avec succ??s'
            );
        } else {
            $this->addFlash(
                'error',
                'not exist'
            );
        }
        return $this->redirectToRoute('people.pagination');
    }
    #[Route("/testmail", name: "testmail")]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        // dd($mailer);
        $mailer->send($email);
        return $this->render('people/testmail.html.twig');
    }
}