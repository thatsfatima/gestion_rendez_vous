<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_SECRETAIRE')]
class SecretaireController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        $demandesEnAttente = $rendezVousRepository->findByStatut('demande');
        
        $today = new \DateTime('today');
        $rdvDuJour = $rendezVousRepository->findByDate($today);
        
        return $this->render('secretaire/dashboard.html.twig', [
            'demandesEnAttente' => $demandesEnAttente,
            'rdvDuJour' => $rdvDuJour,
        ]);
    }
    
    #[Route('/rdv/{id}/valider', name: 'admin_valider_rdv')]
    public function validerRdv(RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        $rendezVous->setStatut('valide');
        $entityManager->flush();
        
        $this->addFlash('success', 'Le rendez-vous a été validé avec succès.');
        return $this->redirectToRoute('admin_dashboard');
    }
    
    #[Route('/rdv/{id}/refuser', name: 'admin_refuser_rdv')]
    public function refuserRdv(RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        $rendezVous->setStatut('annule');
        $entityManager->flush();
        
        $this->addFlash('success', 'Le rendez-vous a été refusé.');
        return $this->redirectToRoute('admin_dashboard');
    }
    
    #[Route('/rdv/liste', name: 'admin_liste_rdv')]
    public function listeRdv(RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVous = $rendezVousRepository->findAll();
        
        return $this->render('secretaire/liste_rdv.html.twig', [
            'rendezVous' => $rendezVous,
        ]);
    }
}