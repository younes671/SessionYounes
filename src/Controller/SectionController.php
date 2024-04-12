<?php

namespace App\Controller;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SectionController extends AbstractController
{
    #[Route('/section', name: 'app_section')]
    public function index(SectionRepository $sectionRepository): Response
    {
        $sections = $sectionRepository->findBy([], ["id" => "ASC"]);
        return $this->render('section/index.html.twig', [
            'sections' => $sections
        ]);
    }

     // crée le formulaire et rajoute session dans bdd ou modifie session 
     #[Route('section/new', name: 'new_section')]
     #[Route('/section/{id}/edit', name: 'edit_section')]
     public function new_edit(Section $section = null, Request $request, EntityManagerInterface $entityManager): Response
     {
         if($this->getUser())
         {
             if(!$section)
             {
                 $section = new Section();
             }
             
             $form = $this->createForm(SectionType::class, $section);
     
             $form->handleRequest($request);
     
             if($form->isSubmitted() && $form->isValid())
             {
                 $employe = $form->getData();
                 //prepare PDO
                 $entityManager->persist($employe);
                 //execute PDO
                 $entityManager->flush();
     
                 return $this->redirectToRoute('app_section');
             }
        
             return $this->render('section/new.html.twig', [
                 'formAddSection' => $form,
                 'edit' => $section->getId()
             ]);
         }else
         {
             return $this->redirectToRoute('app_login');
         }
 
     }

     // supprime module 
     #[Route('/section/{id}/deleteModule', name: 'deleteModule_section')]
     public function deleteModule(Section $section, EntityManagerInterface $entityManager): Response
     {
         
         $entityManager->remove($section);
         $entityManager->flush();
 
         return $this->redirectToRoute('app_section', ['id' => $section->getId()]);
     }
}
