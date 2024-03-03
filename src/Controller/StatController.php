<?php

namespace App\Controller;

use App\Repository\CreditRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    #[Route('/stat', name: 'app_stat')]
    public function index(CreditRepository $creditRepository): Response
    {
        $stats = $creditRepository->getStatsByType();
        return $this->render('stat/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}
