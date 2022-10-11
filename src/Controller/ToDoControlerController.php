<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoControlerController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'debug' => 'revoir mon code'
            ];

            $session->set('todos', $todos);
            $this->addFlash('info', "La liste todos viens d'être initialisée");
        }

        return $this->render('to_do_controler/todo.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo_add')]
    public function addToDo(Request $request, $name, $content)

    {
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');

            if (isset($todos[$name])) {
                $this->addFlash('error', "Le ToDo d'id $name existe déjà dans la liste");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo d'id $name à été ajouté avec succès");
                $session->set('todos', $todos);
            }
        } else {

            $this->addFlash('error', "La liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }
}