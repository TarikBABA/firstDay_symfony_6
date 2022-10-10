<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session = $request->getSession(); //session_start()

        if ($session->has('nmberVisite')) {
            $nmberVisite = $session->get('nmberVisite') + 1;
        } else {
            $nmberVisite = 1;
        }
        $session->set('nmberVisite', $nmberVisite);

        return $this->render('session/index.html.twig');
    }
}