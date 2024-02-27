<?php

namespace App\Controller;

use App\Entity\Compteep;
use App\Entity\Typetaux;
use App\Form\CompteepType;
use App\Repository\CompteepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compteep')]
class CompteepController extends AbstractController
{
    #[Route('/', name: 'app_compteep_index', methods: ['GET'])]
public function index(CompteepRepository $compteepRepository): Response
{
    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    // Récupérer l'entité Client associée à l'utilisateur
    $client = $user->getClient();

    // Vérifier si l'utilisateur a un client associé
    if (!$client) {
        throw $this->createAccessDeniedException('No client associated with the user.');
    }

    // Récupérer les comptes épargne associés au client
    $compteeps = $compteepRepository->findBy(['client' => $client, 'etat' => true]); // Ajouter la condition pour les comptes actifs

    return $this->render('compteep/index.html.twig', [
        'compteeps' => $compteeps,
    ]);
}

    

#[Route('/new', name: 'app_compteep_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $client = $user->getClient();

    $compteep = new Compteep();
    $compteep->setSolde(0);
    $compteep->setEtat(1);
    $compteep->setClient($client);
    $compteep->setDateOuv(new \DateTime());

    do {
        $Rib = mt_rand(10000000000, 99999999999);
    } while ($this->isRibUnique($Rib, $entityManager));

    $compteep->setRib($Rib);

    $form = $this->createForm(CompteEpType::class, $compteep);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le type choisi par le client
        $typeChoisi = $compteep->getType();

        // Vérifier si le type choisi est valide en recherchant dans la table Typetaux
        $typetaux = $entityManager->getRepository(Typetaux::class)->findOneBy(['type' => $typeChoisi]);

        if (!$typetaux) {
            // Si le Typetaux n'existe pas, ajoutez une erreur au formulaire
            $form->get('type')->addError(new FormError('Type choisi non valide.'));
            
            return $this->render('compteep/new.html.twig', [
                'compteep' => $compteep,
                'form' => $form->createView(),
            ]);
        }

        // Associer le Typetaux au Compteep
        $compteep->setTypetaux($typetaux);

        $entityManager->persist($compteep);
        $entityManager->flush();

        return $this->redirectToRoute('app_compteep_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('compteep/new.html.twig', [
        'compteep' => $compteep,
        'form' => $form->createView(),
    ]);
}
    private function isRibUnique($Rib, EntityManagerInterface $entityManager): bool
    {
        // Check if the rib already exists in the database
        $existingCompteEp = $entityManager->getRepository(Compteep::class)->findOneBy(['Rib' => $Rib]);
    
        return $existingCompteEp !== null;
    }

    #[Route('/{id}', name: 'app_compteep_show', methods: ['GET'])]
    public function show(Compteep $compteep): Response
    {
        return $this->render('compteep/show.html.twig', [
            'compteep' => $compteep,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_compteep_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Compteep $compteep, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteepType::class, $compteep, ['is_edit' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compteep_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compteep/edit.html.twig', [
            'compteep' => $compteep,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compteep_delete', methods: ['POST'])]
    public function delete(Request $request, Compteep $compteep, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteep->getId(), $request->request->get('_token'))) {
            $entityManager->remove($compteep);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_compteep_index', [], Response::HTTP_SEE_OTHER);
    }

   

    
}
