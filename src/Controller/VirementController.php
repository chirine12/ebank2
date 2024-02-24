<?php

namespace App\Controller;

use App\Entity\Virement;
use App\Entity\User;
use App\Form\VirementType;
use App\Repository\VirementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/virement')]
class VirementController extends AbstractController
{
    #[Route('/', name: 'app_virement_index', methods: ['GET'])]
    public function index(VirementRepository $virementRepository): Response
    {
        return $this->render('virement/index.html.twig', [
            'virements' => $virementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_virement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $virement = new Virement();
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($virement);
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement/new.html.twig', [
            'virement' => $virement,
            'form' => $form,
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
    #[Route('/virement', name: 'app')]
    public function effectuerVirement(): Response
{
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('login_route'); // Assurez-vous que l'utilisateur est connecté
    }

    $client = $user->getClient(); // Récupérer le client associé à l'utilisateur
    if (!$client) {
        // Gérer le cas où le client n'existe pas
    }

    $compteCourant = $client->getCompteCourant(); // Récupérer le compte courant du client
    if (!$compteCourant) {
        // Gérer le cas où le compte courant n'existe pas
    }

    $rib = $compteCourant->getRib(); // Récupérer le RIB du compte courant

    // Ici, vous pouvez passer le RIB à votre formulaire ou effectuer d'autres actions nécessaires
    // Par exemple, en le pré-remplissant dans un champ de formulaire
}

}
