<?php

namespace App\Controller;


use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{

    #[Route('stagiaire/new', name: 'new_stagiaire')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stagiaire = new Stagiaire();
        
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
        ]);
    }
    
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
        ]);
    }
}
