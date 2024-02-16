<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profileinterface', name: 'app_profile')]
    public function index(): Response
    {
        // Get the current user's username and roles
        $userName = $this->getUser()->getUsername();
        $roles = $this->getUser()->getRoles();
        
        // Check if the user has any roles
        if (!empty($roles)) {
            // Get the first role
            $role = $roles[0];
        } else {
            // Set a default role if the user has no roles
            $role = 'No Role Assigned';
        }

        // Render the template with the user name and role
        return $this->render('profileinterface/index.html.twig', [
            'email' => $userName,
            'role' => $role
        ]);
    }
}
