<?php

namespace App\Controller;

use App\Entity\Section;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\StagiaireType;
use App\Repository\ProgrammeRepository;
use App\Repository\SectionRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
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
        if($this->getUser())
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
        }else
        {
            return $this->redirectToRoute('app_login');
        }

    }

    // supprime session 
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
    }

    // détail programme de session
    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, ProgrammeRepository $programmeRepository, StagiaireRepository $stagiaireRepository): Response
    {
        $prog = $programmeRepository->findBy(['session' => $session]);
        return $this->render('session/show.html.twig', [
            'sessions' => $session,
            'progs' => $prog
        ]);
    }

    // affiche liste des stagiaire inscrit à une session
    #[Route('/session/{id}/listInscription', name: 'listInscription_session')]
    public function listInscription(Session $session, StagiaireRepository $stagiaireRepository): Response
    {
        
        $inscription = $session->getInscriptions();
        $stagiaires = $stagiaireRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('session/listInscription.html.twig', [
            'sessions' => $session,
            'stagiaires' => $stagiaires,
            'inscriptions' => $inscription
        ]);
    }

    #[Route('/session/{id}/addStagiaire', name: 'addStagiaire_session')]
    public function addStagiaire(Request $request, $id, StagiaireRepository $stagiaireRepository, SessionRepository $sessionRepository, EntityManagerInterface $entityManager)
    {
        // Récupérer la session en fonction de l'ID passé dans l'URL
        $session = $sessionRepository->find($id);

        // Vérifier si la session existe
        if (!$session) {
            echo 'La session n\'existe pas.';
        }

        // Récupérer l'ID du stagiaire à partir des données du formulaire
        $stagiaireId = $request->request->get('stagiaire');

        // Récupérer le stagiaire en fonction de l'ID
        $stagiaire = $stagiaireRepository->find($stagiaireId);

        // Vérifier si le stagiaire existe
        if (!$stagiaire) {
            echo 'Le stagiaire n\'existe pas.';
        }

        // Ajouter le stagiaire à la session
        $session->addInscription($stagiaire);

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Rediriger vers une page de confirmation ou autre
        return $this->redirectToRoute('listInscription_session', ['id' => $session->getId()]);
        // return $this->redirectToRoute('addStagiaire_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{id}/deleteStagiaire/{inscriptionId}', name: 'deleteStagiaire_session')]
    public function deleteStagiaireSession(Session $session, Stagiaire $inscriptionId, EntityManagerInterface $entityManager): Response
    {
        $session->removeInscription($inscriptionId);
        $entityManager->flush();

        return $this->redirectToRoute('listInscription_session', ['id' => $session->getId()]);
    }

}


