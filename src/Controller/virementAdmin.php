<?php

namespace App\Controller;

use App\Entity\Virement;
use App\Form\VirementType;
use App\Repository\VirementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Adminvirement')]
class virementAdmin extends AbstractController
{
    #[Route('/', name: 'app_virement_index2', methods: ['GET'])]
    public function index(VirementRepository $virementRepository): Response
    {$virements = $virementRepository->findAll();
    
        return $this->render('adminVB/index.html.twig', [
            'virements' => $virements,
        ]);
    }

    #[Route('/new', name: 'app_virement_new2', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $virement = new Virement();
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($virement);
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adminVB/new.html.twig', [
            'virement' => $virement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_virement_show2', methods: ['GET'])]
    public function show(Virement $virement): Response
    {
        return $this->render('adminVB/show.html.twig', [
            'virement' => $virement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_virement_edit2', methods: ['GET', 'POST'])]
    public function edit(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adminVB/edit.html.twig', [
            'virement' => $virement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_virement_delete2', methods: ['POST'])]
    public function delete(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$virement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($virement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_virement_index2', [], Response::HTTP_SEE_OTHER);
    }
}