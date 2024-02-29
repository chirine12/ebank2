<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    #[Route('/profileinterface', name: 'app_profile')]
    public function index(): Response
    {
        // Check if the user is authenticated
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Get the current user's username and roles
        $userName = $this->getUser()->getUsername();
        $roles = $this->getUser()->getRoles();

        // Check if the user has the ROLE_USER role
        $role = in_array('ROLE_USER', $roles) ? 'ROLE_USER' : 'No Role Assigned';

        // Fetch clients from the repository
        $clients = $this->getUser()->getClient();

        // Count the number of compteep related to each client
        $compteepCounts = [];
        foreach ($clients as $client) {
            $compteepCounts[$client->getId()] = count($client->getCompteeps());
        }

        // Render the template with the user name, role, clients, and compteep counts
        return $this->render('profileinterface/index.html.twig', [
            'email' => $userName,
            'client' => $clients,
            'role' => $role,
            'compteepCounts' => $compteepCounts,
        ]);
    }
}
