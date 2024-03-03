<?php

namespace App\Controller;

use App\Entity\DemandeDesacCE;
use App\Form\DemandeDesacCEType;
use App\Repository\DemandeDesacCERepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Compteep;


#[Route('/demande/desac/c/e')]
class DemandeDesacCEController extends AbstractController
{
    #[Route('/', name: 'app_demande_desac_c_e_index', methods: ['GET'])]
    public function index(DemandeDesacCERepository $demandeDesacCERepository): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();
    
        // Récupérer l'entité Client associée à l'utilisateur
        $client = $user->getClient();
    
        // Vérifier si l'utilisateur a un client associé
        if (!$client) {
            throw $this->createAccessDeniedException('No client associated with the user.');
        }
    
        // Récupérer les demandes de désactivation associées au client
        $demandes = $demandeDesacCERepository->findBy(['client' => $client]);
    
        return $this->render('demande_desac_ce/index.html.twig', [
            'demande_desac_c_es' => $demandes,
        ]);
    }
    

    #[Route('/new/{compteepId}', name: 'app_demande_desac_c_e_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $entityManager,
    Security $security,
    $compteepId,
    DemandeDesacCERepository $demandeDesacCERepository
): Response {
    // Récupérer l'utilisateur actuellement connecté
    $user = $security->getUser();

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        // Gérer le cas où l'utilisateur n'est pas connecté
        // Vous pouvez ajouter une redirection ou afficher un message d'erreur
        return $this->redirectToRoute('login'); // Exemple de redirection vers la page de connexion
    }

    // Récupérer le compte associé à l'ID passé dans la route
    $compteep = $entityManager->getRepository(Compteep::class)->find($compteepId);

    if (!$compteep) {
        throw $this->createNotFoundException('Compteep not found');
    }

    // Vérifier si une demande existe déjà pour ce compte
    if ($demandeDesacCERepository->hasDemandeForCompteep($compteep)) {
        // Une demande existe déjà pour ce compte, afficher un message flash
        $this->addFlash('danger', 'Une demande existe déjà pour ce compte.');
        $this->get('session')->save();
        // Rediriger vers la liste des demandes
        return $this->redirectToRoute('app_demande_desac_c_e_index', [], Response::HTTP_SEE_OTHER);
    }

    $demandeDesacCE = new DemandeDesacCE();

    // Remplir le champ client avec l'utilisateur connecté
    $demandeDesacCE->setClient($user->getClient());

    // Associer le compte à la demande
    $demandeDesacCE->setCompteep($compteep);

    $form = $this->createForm(DemandeDesacCEType::class, $demandeDesacCE);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($demandeDesacCE);
        $entityManager->flush();

        return $this->redirectToRoute('app_demande_desac_c_e_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('demande_desac_ce/new.html.twig', [
        'demande_desac_c_e' => $demandeDesacCE,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_demande_desac_c_e_show', methods: ['GET'])]
    public function show(DemandeDesacCE $demandeDesacCE): Response
    {
        return $this->render('demande_desac_ce/show.html.twig', [
            'demande_desac_c_e' => $demandeDesacCE,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_desac_c_e_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeDesacCE $demandeDesacCE, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeDesacCEType::class, $demandeDesacCE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_desac_c_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_desac_ce/edit.html.twig', [
            'demande_desac_c_e' => $demandeDesacCE,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_desac_c_e_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeDesacCE $demandeDesacCE, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeDesacCE->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandeDesacCE);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_desac_c_e_index', [], Response::HTTP_SEE_OTHER);
    }
}
