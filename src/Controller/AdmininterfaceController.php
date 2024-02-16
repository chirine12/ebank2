<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdmininterfaceController extends AbstractController
{
    #[Route('/admininterface', name: 'app_admininterface')]
    public function index(): Response
    {
        $userName = $this->getUser()->getUsername(); // Assuming Symfony's security component
        $role = $this->getUser()->getRoles();
        return $this->render('admininterface/index.html.twig', [
            'user_name' => $userName,
            'role' => $role,
        ]);
    }
}
