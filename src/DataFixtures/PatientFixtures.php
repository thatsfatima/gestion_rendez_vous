<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PatientFixtures extends FixtureInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $patients = [
            ['Diaw', 'Amie', 'patient1', 'password', ['Diabète']],
            ['Basse', 'Junior', 'patient2', 'password', ['Hypertension', 'Asthme']],
            ['Nadege', 'Betty', 'patient3', 'password', []]
        ];

        $patientList = [];
        foreach ($patients as $index => $patientData) {
            $patient = new Patient($patientData[2], $patientData[3], $patientData[4]);
            $patient->setNom($patientData[0])
                ->setPrenom($patientData[1])
                ->setPassword($this->passwordHasher->hashPassword($patient, $patientData[3]))
                ->setCode('PAT' . str_pad($index + 1, 3, '0', STR_PAD_LEFT))
                ->setTelephone('76' . rand(1000000, 9999999));
            
            foreach ($patientData[4] as $antNom) {
                $patient->addAntecedantMedical($antecedants[$antNom]);
            }
            
            $manager->persist($patient);
            $patientList[] = $patient;
        }
        $manager->flush();
    }
}