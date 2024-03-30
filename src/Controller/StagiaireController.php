<?php

namespace App\Controller;


use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{

    // récupère liste des stagiaires 
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaire = $stagiaireRepository->findBy([], ["id" => "ASC"]);
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaire
        ]);
    }
    
    // crée le formulaire et rajoute session dans bdd 
    #[Route('stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function new_edit(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$stagiaire)
        {
            $stagiaire = new Stagiaire();
        }
        
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $stagiaire = $form->getData();
            //prepare PDO
            $entityManager->persist($stagiaire);
            //execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');
        }
        
        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form,
            'edit' => $stagiaire->getId()
        ]);
    }
    
     // supprime stagiaire 
     #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
     public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
     {
         $entityManager->remove($stagiaire);
         $entityManager->flush();
 
         return $this->redirectToRoute('app_stagiaire');
     }
 
     // détail stagiaire 
     #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
     public function show(Stagiaire $stagiaire): Response
     {
         return $this->render('stagiaire/show.html.twig', [
             'stagiaire' => $stagiaire
         ]);
     }
}
