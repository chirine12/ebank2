<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;

use App\Entity\Virement;
use App\Repository\VirementRepository;
use App\Repository\UserRepository;
use App\Repository\CarteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdmininterfaceController extends AbstractController
{





    
#[Route('/admininterface/{id}', name: 'app_admininterface_id')]
public function indexById(int $id, ClientRepository $clientRepository): Response
{
    $userName = $this->getUser()->getname();

    $clientData = $clientRepository->getClientDataById($id);

    if (!$clientData) {
        throw $this->createNotFoundException('Client not found');
    }

    return $this->render('admininterface/re.html.twig', [
        'client' => $clientData['client'],
        //'cartes' => $clientData['cartes'],
        //'virements' => $clientData['virements'],
        'user_name' => $userName,
    ]);
}

    #[Route('/admininterface', name: 'app_admininterface')]
    public function index(ClientRepository $clientRepository, UserRepository $userRepository, CarteRepository $carteRepository, VirementRepository $virementRepository): Response
    {
        $userName = $this->getUser()->getname();

        // Ensure $clientRepository and $userRepository are injected and defined
        if (!$clientRepository instanceof ClientRepository || !$userRepository instanceof UserRepository) {
            throw new \LogicException('ClientRepository or UserRepository is not properly injected.');
        }

        // Fetch data from entities
        $clients = $clientRepository->findAll();
        $users = $userRepository->findAll();
        $cartes = $carteRepository->findAll();
        $virements = $virementRepository->findAll();

        // Prepare data for the chart
        $resultats = [];
        foreach ($clients as $client) {
            $resultats[] = [
                'clientId' => $client->getId(),
                'nbVirements' => $this->calculateNumberOfVirements($virements, $client),
            ];
        }

        return $this->render('admininterface/index.html.twig', [
            'cartes' => $cartes,
            'virements' => $virements,
            'data_json' => json_encode($resultats),
            'user_name' => $userName,
            'clients' => $clients,
            'users' => $users,
        ]);
    }

    private function calculateNumberOfVirements(array $virements, Client $client): int
    {
        // Your logic to calculate the number of virements for a client based on virements
        // Implement your business logic here...
        $nbVirements = 0;
        foreach ($virements as $virement) {
            if ($virement->getClient() === $client) {
                $nbVirements++;
            }
        }

        return $nbVirements;
    }
}