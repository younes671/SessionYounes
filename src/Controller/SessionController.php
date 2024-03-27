<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    // récupère liste des sessions 
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findBy([], ["id" => "ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }
    
    // crée le formulaire et rajoute session dans bdd ou modifie session 
    #[Route('session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$session)
        {
            $session = new Session();
        }
        
        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $employe = $form->getData();
            //prepare PDO
            $entityManager->persist($employe);
            //execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }
        // session/new.html.twig-> on définit l'endroit ou on veut faire passer les données, puis les données qu'on veut faire passer [clé=>valeur]
        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId()
        ]);
    }

    // supprime session 
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
    }

    // détail session 
    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
