<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoancalculatorController extends AbstractController
{
    #[Route('/loancalculator', name: 'app_loancalculator')]
    public function index(): Response
    {
        return $this->render('loancalculator/index.html.twig', [
            'controller_name' => 'LoancalculatorController',
        ]);
    }
}
 