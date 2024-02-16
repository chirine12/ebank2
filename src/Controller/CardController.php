<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/cardinterface', name: 'app_card')]
    public function index(): Response
    {
        $userName = $this->getUser()->getUsername();
        $roles = $this->getUser()->getRoles();

        if ($roles[0] === 'ROLE_USER') {
            $role = $roles[0];
        } else {
            $role = 'Unknown Role';
        }

        return $this->render('cardinterface/index.html.twig', [
            'email' => $userName,
            'Main_Balance' => 990.000,
           
            'role' => $role,
            'Investment' =>999
        ]);
    }
    }
