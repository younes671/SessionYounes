<?php

namespace App\Controller;


use Mpdf\Mpdf;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
        if($this->getUser())
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
        }else
        {
            return $this->redirectToRoute('app_login');
        }

    }
    
     // supprime stagiaire 
     #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
     public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
     {
         $entityManager->remove($stagiaire);
         $entityManager->flush();
 
         return $this->redirectToRoute('app_stagiaire');
     }
 
     // détail stagiaire +  récupère  sessions 
     #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
     public function show(Stagiaire $stagiaire, SessionRepository $sessionRepository, StagiaireRepository $stagiaireRepository): Response
     {
         return $this->render('stagiaire/show.html.twig', [
             'stagiaire' => $stagiaire,
         ]);
     }


     #[Route('/send-invoice', name: 'send_invoice')]
     public function sendInvoice(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager): Response
     {
         $stagiaireId = $request->request->get('stagiaireId');
         $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($stagiaireId);
     
         if (!$stagiaire) {
             throw $this->createNotFoundException('Stagiaire non trouvé');
         }
     
         $email = $stagiaire->getEmail();
         $montantPaye = $request->request->get('montantPaye');
         $payeEnIntegraliteValue = $request->request->get('payeEnIntegralite', false);
         $payeEnIntegralite = $payeEnIntegraliteValue !== null ? (float) $payeEnIntegraliteValue : null;
         $nombrePaiements = $request->request->get('nombrePaiements', 1);
     
         $stagiaire->setMontantPaye($montantPaye);
         $stagiaire->setPayeEnIntegralite($payeEnIntegralite);
         $stagiaire->setNombrePaiements($nombrePaiements);
     
         $entityManager->flush();
     
         // Génération du fichier PDF avec mPDF
         $mpdf = new \Mpdf\Mpdf();
         $html = $this->renderView(
            'invoice/email.html.twig',
            ['montant' => $montantPaye, 'payeEnIntegralite' => $payeEnIntegralite, 'nombrePaiements' => $nombrePaiements]
        );
         $mpdf->WriteHTML($html);
         $pdfContent = $mpdf->Output('', 'S');
     
         // Chemin pour sauvegarder le fichier PDF dans le dossier Documents
         $pdfDirectory = 'C:\\Users\\youne\\OneDrive\\Documents\\';
         $pdfFileName = 'invoice.pdf';
         $pdfFilePath = $pdfDirectory . $pdfFileName;
     
         // Sauvegarde du fichier PDF
         file_put_contents($pdfFilePath, $pdfContent);
     
         // Création et envoi de l'e-mail
         $email = (new Email())
             ->from(new Address('d8275500@gmail.com', 'Your Name'))
             ->to($email)
             ->subject('Invoice')
             ->attachFromPath($pdfFilePath) // Attache le fichier PDF à l'e-mail
             ->html($html); // Utilisation du contenu HTML directement
    //  dump($mailer); exit;
         // Envoyer l'e-mail
         $sentEmailCount = $mailer->send($email);
# 1C8GZPYQLDFNUD36WK4CZG8G
     
         try {
            $sentEmailCount = $mailer->send($email);
        
            if ($sentEmailCount > 0) {
                // Le fichier PDF a été généré avec succès et l'e-mail a été envoyé
                echo 'Facture générée et e-mail envoyé avec succès.';
            } else {
                // Il y a eu une erreur lors de l'envoi de l'e-mail
                echo 'Erreur lors de l\'envoi de l\'e-mail. Aucun destinataire n\'a accepté le message.';
            }
        } catch (\Exception $e) {
            echo 'Une exception s\'est produite lors de l\'envoi de l\'e-mail : ' . $e->getMessage();
        }
        
     
         // Retournez une réponse pour indiquer que l'e-mail a été envoyé
         return $this->render('stagiaire/show.html.twig', [
             'stagiaire' => $stagiaire
         ]);
     }

     


    #[Route('/stagiaire/{id}/invoice', name: 'invoice')]
    public function invoice(Stagiaire $stagiaire): Response
    {
        // Ici, vous pouvez récupérer le stagiaire depuis la base de données en utilisant $id
        // Puis, passez-le à votre template et affichez-le

        return $this->render('stagiaire/invoice.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

}
