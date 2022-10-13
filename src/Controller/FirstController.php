<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\TwigExtensions;
// use App\TwigExtensions\MyCustomTwigExtensions;

class FirstController extends AbstractController
{
    #[Route('/template', name: 'template')]
    public function template()
    {
        return $this->render('template.html.twig');
    }

    #[Route('/order/{var}', name: 'test.order.route')]
    public function testOrderRoute($var)
    {
        return new Response("<html><body> $var</body></html>");
    }

    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => 'BABA',
            'firstName' => 'Tarik'
        ]);
    }

    // #[Route('/sayHola/{name}/{firstName}', name: 'say_Hola')]
    public function sayHola(Request $request, $name, $firstName): Response
    {
        // dd($request);
        return $this->render('first/hola.html.twig', [
            'nom' => $name,
            'prénom' => $firstName,
            // 'path' => '   '
        ]);
    }

    #[Route('/first/multi/{entier1<\d+>}/{entier2<\d+>}', name: 'multiplication')]
    public function multiplication($entier1, $entier2)
    {
        $result = $entier1 * $entier2;
        return new Response("<h1>$result</h1>");
    }
}