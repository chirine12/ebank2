<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/beneficiaire')]
class BeneficiaireController extends AbstractController
{
    #[Route('/', name: 'app_beneficiaire_index', methods: ['GET'])]
    public function index(BeneficiaireRepository $beneficiaireRepository, ClientRepository $clientRepository): Response
    { $user = $this->getUser();
        $client = $clientRepository->findOneBy(['user' => $user]);
        if (!$client) {
            throw $this->createNotFoundException('Le client associé à l\'utilisateur connecté n\'existe pas.');
        }
    
        $beneficiaires = $beneficiaireRepository->findBy(['client' => $client]);
        return $this->render('beneficiaire/index.html.twig', [
            'beneficiaires' => $beneficiaires,
            'client' => $client,
        ]);
    }

    #[Route('/new', name: 'app_beneficiaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $beneficiaire = new Beneficiaire();
        $user = $this->getUser();

        // Récupérer l'entité Client associée à l'utilisateur
        $client = $user->getClient(); 
         $beneficiaire->setClient($client);
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_beneficiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('beneficiaire/new.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_beneficiaire_show', methods: ['GET'])]
    public function show(Beneficiaire $beneficiaire): Response
    {
        return $this->render('beneficiaire/show.html.twig', [
            'beneficiaire' => $beneficiaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_beneficiaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Beneficiaire $beneficiaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_beneficiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('beneficiaire/edit.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_beneficiaire_delete', methods: ['POST'])]
    public function delete(Request $request, Beneficiaire $beneficiaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$beneficiaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($beneficiaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_beneficiaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search/beneficiary/by-name', name:'app_search_benef_by_name', methods:["POST"])]
 
public function searchBeneficiaryByName(Request $request, BeneficiaireRepository $beneficiaireRepository): Response
{
    $name = $request->request->get('name');
    $beneficiaires = $beneficiaireRepository->findByName($name);

    // Retourner les résultats sous forme de vue ou JSON
    return $this->render('beneficiaire/search_results.html.twig', [
        'beneficiaires' => $beneficiaires,
    ]);
}
}
