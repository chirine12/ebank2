<?php

namespace App\Controller;

use App\Entity\Compteep;
use App\Repository\DemandeDesacCERepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAcceptController extends AbstractController
{
    #[Route('/admin/accept/index', name: 'app_admin_accept_index')]
    public function index(DemandeDesacCERepository $demandeDesacCERepository): Response
    {
        $demandes = $demandeDesacCERepository->findAll();
        return $this->render('admin_accept/index.html.twig', [
            'controller_name' => 'AdminAcceptController',
            'demande_desac_c_es' => $demandes,
        ]);
    }

    #[Route('/admin/accept/{id}', name: 'app_admin_accept', methods: ['GET'])]
public function accept(
    DemandeDesacCERepository $demandeDesacCERepository,
    EntityManagerInterface $entityManager,
    $id
): RedirectResponse {
    $demande = $demandeDesacCERepository->find($id);

    if (!$demande) {
        $this->addFlash('danger', 'Demande non trouvée.');
        return $this->redirectToRoute('app_admin_accept_index');
    }

    // Vérifier le solde du compte avant la désactivation
    $compte = $demande->getCompteep();

    if ($compte instanceof Compteep) {
        $solde = $compte->getSolde();

        if ($solde < 0) {
            $this->addFlash('danger', 'Impossible de désactiver le compte, solde négatif.');
            return $this->redirectToRoute('app_admin_accept_index');
        }

        // Mettre à jour l'état du compte
        if ($compte->isEtat() !== null) {
            $compte->setEtat(false); // Or set it to true if that's the expected behavior.
            $entityManager->persist($compte);
        }

        // Supprimer la demande
        $entityManager->remove($demande);
        $entityManager->flush();

        $this->addFlash('success', 'Demande acceptée avec succès.');
        return $this->redirectToRoute('app_admin_accept_index');
    }

    $this->addFlash('danger', 'Compte non trouvé.');
    return $this->redirectToRoute('app_admin_accept_index');
}


#[Route('/admin/refuse/{id}', name: 'app_admin_refuse', methods: ['GET'])]
public function refuse(
    DemandeDesacCERepository $demandeDesacCERepository,
    EntityManagerInterface $entityManager,
    $id
): RedirectResponse {
    $demande = $demandeDesacCERepository->find($id);

    if (!$demande) {
        $this->addFlash('danger', 'Demande non trouvée.');
        return $this->redirectToRoute('app_admin_accept_index');
    }

    // Supprimer la demande sans désactiver le compte
    $entityManager->remove($demande);
    $entityManager->flush();

    $this->addFlash('success', 'Demande refusée et supprimé.');
    return $this->redirectToRoute('app_admin_accept_index');
}

}
