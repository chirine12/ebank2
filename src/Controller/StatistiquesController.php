<?php

namespace App\Controller;

use App\Entity\Compteep;
use App\Entity\Typetaux;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    #[Route('/statistiques', name: 'app_statistiques')]
    public function index(): Response
    {
        return $this->render('statistiques/index.html.twig', [
            'controller_name' => 'StatistiquesController',
        ]);
    }
    #[Route('/statistiques', name: 'app_statistiques')]
public function statistiques(EntityManagerInterface $entityManager): Response
{
    $repository = $entityManager->getRepository(Compteep::class);

    // Obtenez le nombre de comptes pour chaque client
    $resultats = $repository->createQueryBuilder('c')
        ->select('client.id as clientId, COUNT(c.id) as nbComptes')
        ->leftJoin('c.client', 'client')
        ->groupBy('client.id')
        ->getQuery()
        ->getResult();

    return $this->render('statistiques/index.html.twig', [
        'resultats' => $resultats,
        'resultats_json' => json_encode($resultats),
    ]);
}

    
}
