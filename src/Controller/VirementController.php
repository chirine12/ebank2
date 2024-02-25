<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Comptecourant;
use App\Entity\Virement;

use App\Form\VirementType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ClientRepository;
use App\Repository\VirementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/virement')]
class VirementController extends AbstractController
{
    #[Route('/', name: 'app_virement_index', methods: ['GET'])]
    public function mesVirements(VirementRepository $virementRepository, ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();
        $client = $clientRepository->findOneBy(['user' => $user]);
    
        if (!$client) {
            throw $this->createNotFoundException('Le client associé à l\'utilisateur connecté n\'existe pas.');
        }
    
        $virements = $virementRepository->findBy(['client' => $client]);
    
        return $this->render('virement/index.html.twig', [
            'virements' => $virements,
            'client' => $client,
        ]);
    }

    #[Route('/new', name: 'app_virement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
        }

        $client = $user->getClient(); // Supposons que getUser() retourne un User qui a une méthode getClient()

        if (!$client) {
            // Gérer l'absence de client associé
            $this->addFlash('error', 'Aucun client associé à cet utilisateur.');
            return $this->redirectToRoute('app_virement_index'); // Ou une autre page appropriée
        }

        $compteCourant = $client->getCompteCourant();
         // Supposons que getClient() retourne un Client qui a une méthode getCompteCourant()

        if (!$compteCourant) {
            // Gérer l'absence de compte courant associé
            $this->addFlash('error', 'Aucun compte courant associé à ce client.');
            return $this->redirectToRoute('app_virement_index'); // Ou une autre page appropriée
        }

        $rib = $compteCourant->getRib(); // Supposons que getCompteCourant() retourne un CompteCourant qui a une méthode getRib()

        $virement = new Virement();
        $virement->setSource($rib); // Pré-remplir le champ source avec le RIB du client
        $virement->setClient($client);
        $beneficiaireId = $request->query->get('beneficiaireId'); // Récupère l'ID du bénéficiaire depuis les paramètres de requête

if ($beneficiaireId) {
    $beneficiaireRepository = $entityManager->getRepository(Beneficiaire::class);
    $beneficiaire = $beneficiaireRepository->find($beneficiaireId);

    if ($beneficiaire) {
        $virement->setDestinataire($beneficiaire->getRib()); // Pré-remplir le champ destinataire avec le RIB du bénéficiaire
    } else {
        // Gérer le cas où le bénéficiaire n'est pas trouvé
        $this->addFlash('error', 'Bénéficiaire non trouvé.');
        
    }
}
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteSource = $client->getCompteCourant();
    $compteDestinataire = $entityManager->getRepository(Comptecourant::class)->findOneBy(['Rib' => $virement->getDestinataire()]);
    
    if ($compteSource->getSolde() >= $virement->getMontant()) {
        // Mise à jour des soldes
        $compteSource->setSolde($compteSource->getSolde() - $virement->getMontant());
        $compteDestinataire->setSolde($compteDestinataire->getSolde() + $virement->getMontant());

        $entityManager->persist($compteSource);
        $entityManager->persist($compteDestinataire);

            $entityManager->persist($virement);
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }
        else {
            // Gérer le cas où le solde est insuffisant
            $soldeInsuffisantErreur = 'Solde insuffisant pour effectuer le virement.';
        
        }

       
    }
    return $this->render('virement/new.html.twig', [
        'virement' => $virement,
        'form' => $form->createView(),
        'soldeInsuffisantErreur' => $soldeInsuffisantErreur,
    ]);
}

    #[Route('/{id}', name: 'app_virement_show', methods: ['GET'])]
    public function show(Virement $virement): Response
    {
        return $this->render('virement/show.html.twig', [
            'virement' => $virement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_virement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement/edit.html.twig', [
            'virement' => $virement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_virement_delete', methods: ['POST'])]
    public function delete(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$virement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($virement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
    }
    
    
}
