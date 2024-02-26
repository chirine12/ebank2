<?php

// src/Command/CalculInteretCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Compteep;

class CalculInteretCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // Appel du constructeur parent avec le nom de la commande
        parent::__construct('calcul-interet');

        // Injection de l'EntityManager dans la propriété privée
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Effectue le calcul d\'intérêt au début de chaque mois');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
            $output->writeln('Command executed at ' . (new \DateTime())->format('Y-m-d H:i:s'));
        // Récupérer tous les comptes épargnes
        $comptesEpargnes = $this->entityManager->getRepository(Compteep::class)->findAll();

        foreach ($comptesEpargnes as $compteEpargne) {

            if ($compteEpargne->isEtat()) {
            // Supposons que chaque Compteep a un Typetaux associé
            $typetaux = $compteEpargne->getTypetaux();
            dump( $typetaux);
            // Assurez-vous que Typetaux est bien récupéré
            if ($typetaux && $typetaux->getTaux() !== null) {
                // Récupérer le solde actuel du compte et le taux d'intérêt associé
                $soldeActuel = $compteEpargne->getSolde();
                $tauxInteret = $typetaux->getTaux();
                
dump($soldeActuel);
dump($tauxInteret);
                // Calcul de l'intérêt mensuel
                $interetMensuel = $soldeActuel * ($tauxInteret / 100);
                dump($interetMensuel);
                // Ajout de l'intérêt au solde actuel
                $nouveauSolde = $soldeActuel + $interetMensuel;
                dump($nouveauSolde);
                // Mise à jour du solde dans la table des comptes épargnes
                $compteEpargne->setSolde($nouveauSolde);

                // Enregistrez l'entité dans la base de données
                $this->entityManager->persist($compteEpargne);
            }
        }
    }
        // Enregistrez toutes les modifications dans la base de données
        $this->entityManager->flush();

        $output->writeln('Calcul d\'intérêt effectué avec succès.');
        return Command::SUCCESS;
    }
}

