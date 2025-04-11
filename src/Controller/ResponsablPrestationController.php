<?php

// src/Controller/ResponsablePrestationController.php
namespace App\Controller;

use App\Entity\Prestation;
use App\Form\ResultatPrestationType;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/responsable')]
#[IsGranted('ROLE_RESPONSABLE')]
class ResponsablePrestationController extends AbstractController
{
    #[Route('/', name: 'responsable_dashboard')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        // Prestations du jour
        $today = new \DateTime('today');
        $prestationsDuJour = $prestationRepository->findByDate($today);
        
        // Prestations en attente de résultats
        $prestationsEnAttente = $prestationRepository->findValidatedWithoutResults();
        
        return $this->render('responsable/dashboard.html.twig', [
            'prestationsDuJour' => $prestationsDuJour,
            'prestationsEnAttente' => $prestationsEnAttente,
        ]);
    }
    
    #[Route('/prestations', name: 'responsable_prestations')]
    public function prestations(
        Request $request, 
        PrestationRepository $prestationRepository
    ): Response
    {
        $dateDebut = $request->query->get('date_debut') ? new \DateTime($request->query->get('date_debut')) : null;
        $dateFin = $request->query->get('date_fin') ? new \DateTime($request->query->get('date_fin')) : null;
        
        $prestations = $prestationRepository->findWithDateRange($dateDebut, $dateFin);
        
        return $this->render('responsable/prestations.html.twig', [
            'prestations' => $prestations,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);
    }
    
    #[Route('/prestation/{id}', name: 'responsable_prestation_details')]
    public function prestationDetails(
        Prestation $prestation, 
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(ResultatPrestationType::class, $prestation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Les résultats de la prestation ont été enregistrés avec succès.');
            return $this->redirectToRoute('responsable_prestations');
        }
        
        return $this->render('responsable/prestation_details.html.twig', [
            'prestation' => $prestation,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/prestation/{id}/annuler', name: 'responsable_annuler_prestation')]
    public function annulerPrestation(Prestation $prestation, EntityManagerInterface $entityManager): Response
    {
        if (!$prestation->peutEtreAnnuleAnnulee()) {
            $this->addFlash('error', 'La prestation ne peut pas être annulée.');
            return $this->redirectToRoute('responsable_prestations');
        }

        $entityManager->remove($prestation);
        $entityManager->flush();

        $this->addFlash('success', 'La prestation a été annulée avec succès.');
        return $this->redirectToRoute('responsable_prestations');
    }
    
    #[Route('/prestation/{id}/valider', name: 'responsable_valider_prestation')]
    public function validerPrestation(Prestation $prestation, EntityManagerInterface $entityManager): Response
    {
        $prestation->setValidee(true);
        $entityManager->flush();

        $this->addFlash('success', 'La prestation a été validée avec succès.');
        return $this->redirectToRoute('responsable_prestations');
    }
    
}