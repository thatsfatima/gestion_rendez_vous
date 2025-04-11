<?php

namespace App\Controller;

use App\Entity\Patient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    /**
     * @Route("/patients/register", name="patient_register")
     */
    public function register(Request $request)
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
        }

        return $this->render('patient/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/patients/login", name="patient_login")
     */
    public function login(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('patient/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/patients/logout", name="patient_logout")
     */
    public function logout()
    {
        // This method is never called, Symfony handles the logout automatically
    }
}