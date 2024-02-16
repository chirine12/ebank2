<?php

namespace App\Controller;

use App\Entity\Compteep;
use App\Form\CompteepType;
use App\Repository\CompteepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compteep')]
class CompteepController extends AbstractController
{
    #[Route('/', name: 'app_compteep_index', methods: ['GET'])]
    public function index(CompteepRepository $compteepRepository): Response
    {
        return $this->render('compteep/index.html.twig', [
            'compteeps' => $compteepRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_compteep_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compteep = new Compteep();
        $form = $this->createForm(CompteepType::class, $compteep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteep);
            $entityManager->flush();

            return $this->redirectToRoute('app_compteep_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compteep/new.html.twig', [
            'compteep' => $compteep,
            'form' => $form,
        ]);
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
        $form = $this->createForm(CompteepType::class, $compteep);
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
