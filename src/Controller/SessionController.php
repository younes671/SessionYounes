<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    
    #[Route('session/new', name: 'new_session')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();
        
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
        
        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
        ]);
    }

    #[Route('/session', name: 'app_session')]
    public function index(): Response
    {
        return $this->render('session/index.html.twig', []);
    }
}
