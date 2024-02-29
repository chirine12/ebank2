<?php

namespace App\Controller;

use App\Entity\Comptecourant;
use App\Form\ComptecourantType;
use App\Repository\ComptecourantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comptecourant')]
class ComptecourantController extends AbstractController
{
    #[Route('/', name: 'app_comptecourant_index', methods: ['GET'])]
    public function index(ComptecourantRepository $comptecourantRepository): Response
    {
        return $this->render('comptecourant/index.html.twig', [
            'comptecourants' => $comptecourantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comptecourant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Manually set the ID (replace 123 with your desired ID)
        $comptecourant = (new Comptecourant())->setId($this->getUser()->getId());

        $form = $this->createForm(ComptecourantType::class, $comptecourant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comptecourant);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_comptecourant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptecourant/new.html.twig', [
            'comptecourant' => $comptecourant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comptecourant_show', methods: ['GET'])]
    public function show(Comptecourant $comptecourant): Response
    {
        return $this->render('comptecourant/show.html.twig', [
            'comptecourant' => $comptecourant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comptecourant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comptecourant $comptecourant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComptecourantType::class, $comptecourant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comptecourant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comptecourant/edit.html.twig', [
            'comptecourant' => $comptecourant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comptecourant_delete', methods: ['POST'])]
    public function delete(Request $request, Comptecourant $comptecourant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comptecourant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comptecourant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comptecourant_index', [], Response::HTTP_SEE_OTHER);
    }
}
