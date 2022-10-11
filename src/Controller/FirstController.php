<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/first', name: 'app_first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'controller_name' => 'le 1er controller',
        ]);
    }

    #[Route('/sayHola/{name}/{firstName}', name: 'say_Hola')]
    public function sayHola(Request $request, $name, $firstName): Response
    {
        dd($request);
        return $this->render('first/hola.html.twig', [
            'nom' => $name,
            'prénom' => $firstName
        ]);
    }

    // #[Route('/multi/{entier1}/{entier2}')]
    /**
     * @Route("/first/multi/{entier1}/{entier2}")
     */
    public function multiplication($entier1, $entier2)
    {
        $result = $entier1 * $entier2;
        return new Response("<h1>$result</h1>");
    }
}