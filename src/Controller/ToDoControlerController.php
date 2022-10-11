<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

            $this->addFlash('info', "La liste todos viens d'être initialisée");
            $session->set('todos', $todos);
        }

        return $this->render('to_do_controler/todo.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo_add')]
    public function addToDo(Request $request, $name, $content): RedirectResponse

    {
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');

            if (isset($todos[$name])) {
                $this->addFlash('error', "Le ToDo d'id $name existe déjà dans la liste");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo d'id $name a été ajouté avec succès");
                $session->set('todos', $todos);
            }
        } else {

            $this->addFlash('error', "La liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/todo/set/{name}/{content}', name: 'todo_set')]
    public function setToDo(Request $request, $name, $content): RedirectResponse

    {
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');

            if (!isset($todos[$name])) {
                $this->addFlash('error', "Le ToDo d'id $name n'existe pas dans la liste");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo $name a été modifié avec succès");
                $session->set('todos', $todos);
            }
        } else {

            $this->addFlash('error', "La liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/todo/delete/{name}/', name: 'todo_delete')]
    public function deleteToDo(Request $request, $name): RedirectResponse

    {
        $session = $request->getSession();

        if ($session->has('todos')) {
            $todos = $session->get('todos');

            if (!isset($todos[$name])) {
                $this->addFlash('error', "Le ToDo d'id $name n'existe pas dans la liste");
            } else {
                unset($todos[$name]);
                $this->addFlash('success', "Le todo $name a été supprimé avec succès");
                $session->set('todos', $todos);
            }
        } else {
            $this->addFlash('error', "La liste n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/todo/reset/{name}/', name: 'todo_reset')]
    public function resetToDo(Request $request, $name)

    {
        $session = $request->getSession();

        if ($session->has($name)) {
            $this->addFlash('success', "La todos $name a été supprimé avec succès");
            $session->remove($name);
        } else {

            $this->addFlash('error', "La liste n'est pas enregistrée");
        }
        return $this->redirectToRoute('todo');
    }
}