<?php

namespace App\Controller;

use App\Repository\BeneficiaireRepository;
use App\Repository\CompteepRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SerachController extends AbstractController
{
    #[Route('/serach', name: 'app_serach')]
    public function index(): Response
    {
        return $this->render('serach/index.html.twig', [
            'controller_name' => 'SerachController',
        ]);
    }
    #[Route('/search/compteep/by-rib', name: 'app_search_compteep_by_rib', methods: ['POST'])]
public function searchCompteEpByRib(Request  $request, CompteepRepository $compteEpRepository)
{
    $rib = $request->request->get('rib');
    $compteeps = $compteEpRepository->searchByRib($rib);

    return $this->render('compteep/search_results.html.twig', [
        'compteeps' => $compteeps,
    ]);
}
#[Route('/search/beneficiaire/by-name', name: 'app_search_benefeciaire_by_name', methods: ['POST'])]
public function searchBenfEpByName(Request  $request, BeneficiaireRepository $beneficiaireRepository)
{
    $name = $request->request->get('nom');
    $beneficiares = $beneficiaireRepository->searchByName($name);

    return $this->render('beneficiaire/search_results.html.twig', [
        'beneficiaires' => $beneficiares,
    ]);
}
}
