<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Form\ProgrammeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]
    public function index(): Response
    {
        return $this->render('programme/index.html.twig');
    }


// méthode creation programme session 
#[Route('/programme/addProg', name: 'addProg_programme')]
public function create(Request $request, EntityManagerInterface $entityManager): Response
{
    // Création nouvelle instance de Programme
    $programme = new Programme();

    // Création du formulaire ProgrammeType et le liez à l'entité Programme
    $form = $this->createForm(ProgrammeType::class, $programme);

    // Gérez les données du formulaire lorsqu'il est soumis
    $form->handleRequest($request);

    // Vérification soumission et validité formulaire
    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement base de données
        $entityManager->persist($programme);
        $entityManager->flush();

        // renvoie message en cas de succès 
        $this->addFlash('success', 'Le programme a été ajouté avec succès.');

        // Redirection
        return $this->redirectToRoute('addProg_programme');
    }

    // Affichage vue
    return $this->render('programme/prog.html.twig', [
        'form' => $form,
    ]);
}

}
