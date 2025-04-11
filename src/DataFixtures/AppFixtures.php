<?php

namespace App\DataFixtures;

use App\Entity\AntecedantMedical;
use App\Entity\Consultation;
use App\Entity\Medecin;
use App\Entity\Medicament;
use App\Entity\Ordonnance;
use App\Entity\Patient;
use App\Entity\Prestation;
use App\Entity\ResponsablePrestation;
use App\Entity\Secretaire;
use App\Entity\Specialite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $specialites = [
            'Généraliste' => null,
            'Dentiste' => null,
            'Ophtalmologue' => null,
            'Cardiologue' => null
        ];

        foreach ($specialites as $nom => &$specialite) {
            $spec = new Specialite();
            $spec->setLibelle($nom);
            $manager->persist($spec);
            $specialite = $spec;
        }

        $antecedants = [
            'Diabète' => null,
            'Hypertension' => null,
            'Hépatite' => null,
            'Asthme' => null
        ];

        foreach ($antecedants as $nom => &$antecedant) {
            $ant = new AntecedantMedical();
            $ant->setLibelle($nom);
            $manager->persist($ant);
            $antecedant = $ant;
        }

        $medicaments = [
            'PAR001' => 'Paracétamol',
            'IBS002' => 'Ibuprofène',
            'DOL003' => 'Doliprane',
            'AUG004' => 'Augmentin'
        ];

        $medList = [];
        foreach ($medicaments as $code => $nom) {
            $med = new Medicament();
            $med->setCode($code)
                ->setNom($nom);
            $manager->persist($med);
            $medList[] = $med;
        }

        $secretaire = new Secretaire('admin', 'admin');
        $secretaire->setNom('Admin')
            ->setPrenom('System')
            ->setTelephone('123456789')
            ->setPassword($this->passwordHasher->hashPassword($secretaire, 'admin'));
        $manager->persist($secretaire);

        $medecins = [
            ['Diop', 'Fatme', 'medecin1', 'password', $specialites['Généraliste']],
            ['Gackou', 'Bana', 'medecin2', 'password', $specialites['Dentiste']],
            ['Sall', 'Ndiaya', 'medecin3', 'password', $specialites['Ophtalmologue']]
        ];

        $medecinList = [];
        foreach ($medecins as $medecinData) {
            $medecin = new Medecin($medecinData[2], $medecinData[3]);
            $medecin->setNom($medecinData[0])
                ->setPrenom($medecinData[1])
                ->setPassword($this->passwordHasher->hashPassword($medecin, $medecinData[3]))
                ->setSpecialite($medecinData[4])
                ->setTelephone('77' . rand(1000000, 9999999));
            $manager->persist($medecin);
            $medecinList[] = $medecin;
        }

        $responsable = new ResponsablePrestation('responsable', 'password');
        $responsable->setNom('Thiam')
            ->setPrenom('Sophie')
            ->setPassword($this->passwordHasher->hashPassword($responsable, 'password'))
            ->setTelephone('70' . rand(1000000, 9999999));
        $manager->persist($responsable);

        $patients = [
            ['Diaw', 'Amie', 'patient1', 'password', ['Diabète']],
            ['Basse', 'Junior', 'patient2', 'password', ['Hypertension', 'Asthme']],
            ['Nadege', 'Betty', 'patient3', 'password', []]
        ];

        $patientList = [];
        foreach ($patients as $index => $patientData) {
            $patient = new Patient($patientData[2], $patientData[3]);
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

        $dateDebut = new \DateTime('now');

        for ($i = -5; $i <= 10; $i++) {
            $date = clone $dateDebut;
            $date->modify("$i day");
            
            if ($i % 2 == 0) {
                $consultation = new Consultation();
                $consultation->setPatient($patientList[rand(0, count($patientList) - 1)])
                    ->setMedecin($medecinList[rand(0, count($medecinList) - 1)])
                    ->setDate($date)
                    ->setStatut($i < 0 ? 'valide' : ($i == 0 ? 'valide' : 'demande'));
                
                if ($i < 0) {
                    $consultation->setTemperature(rand(365, 380) / 10)
                        ->setTension(rand(100, 140) . ':' . 10)
                        ->setPouls(rand(90, 120));
                    
                    if ($i % 3 == 0) {
                        $ordonnance = new Ordonnance();
                        $ordonnance->setConsultation($consultation)
                            ->setPosologie('1 comprimé 3 fois par jour');
                        
                        for ($j = 0; $j < rand(1, 3); $j++) {
                            $ordonnance->addMedicament($medList[rand(0, count($medList) - 1)]);
                        }
                        
                        $manager->persist($ordonnance);
                    }
                }
                
                $manager->persist($consultation);
            } else {
                $prestation = new Prestation();
                $prestation->setPatient($patientList[rand(0, count($patientList) - 1)])
                    ->setDate($date)
                    ->setLibelle(['Analyse sanguine', 'Radio pulmonaire', 'Scanner', 'IRM'][rand(0, 3)])
                    ->setStatut($i < 0 ? 'valide' : ($i == 0 ? 'valide' : 'demande'));
                
                if ($i < 0) {
                    $prestation->setResultat('Résultats normaux');
                }
                
                $manager->persist($prestation);
            }
        }

        $manager->flush();
    }
    
}