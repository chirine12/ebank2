<?php

namespace App\Controller;

use App\Repository\CreditRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TricreditController extends AbstractController
{
    #[Route('/tricredit', name: 'app_tricredit', methods: ['GET'])]
    public function index(CreditRepository $creditRepository): Response
    {
        $credits = $creditRepository->findAllSortedByType();
        dump($credits);
         return $this->render('tricredit/index.html.twig', [
            'credits' => $credits,
        ]);
    }
  
}
