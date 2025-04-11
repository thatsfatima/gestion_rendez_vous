<?php

// src/Controller/PatientController.php
namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\Patient;
use App\Entity\Prestation;
use App\Entity\RendezVous;
use App\Form\DemandeRdvType;
use App\Repository\MedecinRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/patient')]
#[IsGranted('ROLE_PATIENT')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'patient_dashboard')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        /** @var Patient $patient */
        $patient = $this->getUser();
        
        // Récupérer les rendez-vous à venir
        $rdvAVenir = $rendezVousRepository->findUpcomingForPatient($patient);
        
        // Récupérer l'historique des consultations
        $consultations = $rendezVousRepository->findConsultationsForPatient($patient);
        
        // Récupérer l'historique des prestations
        $prestations = $rendezVousRepository->findPrestationsForPatient($patient);
        
        return $this->render('patient/dashboard.html.twig', [
            'patient' => $patient,
            'rdvAVenir' => $rdvAVenir,
            'consultations' => $consultations,
            'prestations' => $prestations,
        ]);
    }
    
    #[Route('/demande-rdv', name: 'patient_demande_rdv')]
    public function demandeRdv(Request $request, EntityManagerInterface $entityManager, MedecinRepository $medecinRepository): Response
    {
        $form = $this->createForm(DemandeRdvType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $type = $data['type'];
            
            /** @var Patient $patient */
            $patient = $this->getUser();
            
            if ($type === 'consultation') {
                $rdv = new Consultation();
                $rdv->setMedecin($data['medecin']);
            } else {
                $rdv = new Prestation();
                $rdv->setLibelle($data['prestation']);
            }
            
            $rdv->setPatient($patient)
                ->setDate($data['date'])
                ->setStatut('demande');
            
            $entityManager->persist($rdv);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre demande de rendez-vous a été enregistrée et est en attente de validation.');
            return $this->redirectToRoute('patient_dashboard');
        }
        
        return $this->render('patient/demande_rdv.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/rdv/{id}/annuler', name: 'patient_annuler_rdv')]
    public function annulerRdv(RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        /** @var Patient $patient */
        $patient = $this->getUser();
        
        // Vérifier que ce RDV appartient bien au patient connecté
        if ($rendezVous->getPatient()->getId() !== $patient->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à annuler ce rendez-vous.');
            return $this->redirectToRoute('patient_dashboard');
        }
        
        // Vérifier que le RDV peut être annulé (48h avant)
        if (!$rendezVous->peutEtreAnnule()) {
            $this->addFlash('error', 'Un rendez-vous ne peut être annulé moins de 48h avant sa date.');
            return $this->redirectToRoute('patient_dashboard');
        }
        
        $rendezVous->setStatut('annule');
        $entityManager->flush();
        
        $this->addFlash('success', 'Le rendez-vous a été annulé avec succès.');
        return $this->redirectToRoute('patient_dashboard');
    }
    
    #[Route('/mes-consultations', name: 'patient_consultations')]
    public function consultations(RendezVousRepository $rendezVousRepository): Response
    {
        /** @var Patient $patient */
        $patient = $this->getUser();
        
        $consultations = $rendezVousRepository->findConsultationsForPatient($patient);
        
        return $this->render('patient/consultations.html.twig', [
            'consultations' => $consultations,
        ]);
    }
    
    #[Route('/mes-prestations', name: 'patient_prestations')]
    public function prestations(RendezVousRepository $rendezVousRepository): Response
    {
        /** @var Patient $patient */
        $patient = $this->getUser();
        
        $prestations = $rendezVousRepository->findPrestationsForPatient($patient);
        
        return $this->render('patient/prestations.html.twig', [
            'prestations' => $prestations,
        ]);
    }
}