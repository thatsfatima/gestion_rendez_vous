<?php

// src/Controller/MedecinController.php
namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\Medecin;
use App\Entity\Ordonnance;
use App\Entity\Patient;
use App\Entity\RendezVous;
use App\Form\ConsultationType;
use App\Form\OrdonnanceType;
use App\Repository\ConsultationRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/medecin')]
#[IsGranted('ROLE_MEDECIN')]
class MedecinController extends AbstractController
{
    #[Route('/', name: 'medecin_dashboard')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        /** @var Medecin $medecin */
        $medecin = $this->getUser();
        
        $today = new \DateTime('today');
        $rdvDuJour = $rendezVousRepository->findByMedecinAndDate($medecin, $today);
        
        $rdvAVenir = $rendezVousRepository->findUpcomingForMedecin($medecin);
        
        return $this->render('medecin/dashboard.html.twig', [
            'medecin' => $medecin,
            'rdvDuJour' => $rdvDuJour,
            'rdvAVenir' => $rdvAVenir,
        ]);
    }
    
    #[Route('/consultations', name: 'medecin_consultations')]
    public function consultations(
        Request $request, 
        ConsultationRepository $consultationRepository
    ): Response
    {
        /** @var Medecin $medecin */
        $medecin = $this->getUser();
        
        $dateDebut = $request->query->get('date_debut') ? new \DateTime($request->query->get('date_debut')) : null;
        $dateFin = $request->query->get('date_fin') ? new \DateTime($request->query->get('date_fin')) : null;
        
        $consultations = $consultationRepository->findByMedecinWithDateRange($medecin, $dateDebut, $dateFin);
        
        return $this->render('medecin/consultations.html.twig', [
            'consultations' => $consultations,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);
    }
    
    #[Route('/consultation/{id}', name: 'medecin_consultation_details')]
    public function consultationDetails(
        Consultation $consultation, 
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        /** @var Medecin $medecin */
        $medecin = $this->getUser();
        
        if ($consultation->getMedecin()->getId() !== $medecin->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à accéder à cette consultation.');
            return $this->redirectToRoute('medecin_consultations');
        }
        
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'La consultation a été mise à jour avec succès.');
            return $this->redirectToRoute('medecin_consultations');
        }
        
        $ordonnance = $consultation->getOrdonnance() ?? new Ordonnance();
        if (!$consultation->getOrdonnance()) {
            $ordonnance->setConsultation($consultation);
        }
        
        $ordonnanceForm = $this->createForm(OrdonnanceType::class, $ordonnance);
        $ordonnanceForm->handleRequest($request);
        
        if ($ordonnanceForm->isSubmitted() && $ordonnanceForm->isValid()) {
            $entityManager->persist($ordonnance);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'ordonnance a été enregistrée avec succès.');
            return $this->redirectToRoute('medecin_consultation_details', ['id' => $consultation->getId()]);
        }
        
        return $this->render('medecin/consultation_details.html.twig', [
            'consultation' => $consultation,
            'form' => $form->createView(),
            'ordonnanceForm' => $ordonnanceForm->createView(),
        ]);
    }
    
    #[Route('/rdv/{id}/annuler', name: 'medecin_annuler_rdv')]
    public function annulerRdv(RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        /** @var Medecin $medecin */
        $medecin = $this->getUser();
        
        if ($rendezVous->getMedecin()->getId() !== $medecin->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à annuler ce rendez-vous.');
            return $this->redirectToRoute('medecin_dashboard');
        }
        
        $rendezVous->setStatut('annule');
        $entityManager->flush();
        
        $this->addFlash('success', 'Le rendez-vous a été annulé avec succès.');
        return $this->redirectToRoute('medecin_dashboard');
    }
    
    #[Route('/dossier-medical/{id}', name: 'medecin_dossier_medical')]
    public function dossierMedical(Patient $patient, RendezVousRepository $rendezVousRepository): Response
    {
        $consultations = $rendezVousRepository->findConsultationsForPatient($patient);
        
        $prestations = $rendezVousRepository->findPrestationsForPatient($patient);
        
        return $this->render('medecin/dossier_medical.html.twig', [
            'patient' => $patient,
            'consultations' => $consultations,
            'prestations' => $prestations,
        ]);
    }
}