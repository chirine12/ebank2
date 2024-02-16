<?php

namespace App\Controller;

use App\Entity\Typecredit;
use App\Form\TypecreditType;
use App\Repository\TypecreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typecredit')]
class TypecreditController extends AbstractController
{
    #[Route('/', name: 'app_typecredit_index', methods: ['GET'])]
    public function index(TypecreditRepository $typecreditRepository): Response
    {
        return $this->render('typecredit/index.html.twig', [
            'typecredits' => $typecreditRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typecredit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typecredit = new Typecredit();
        $form = $this->createForm(TypecreditType::class, $typecredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typecredit);
            $entityManager->flush();

            return $this->redirectToRoute('app_typecredit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typecredit/new.html.twig', [
            'typecredit' => $typecredit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typecredit_show', methods: ['GET'])]
    public function show(Typecredit $typecredit): Response
    {
        return $this->render('typecredit/show.html.twig', [
            'typecredit' => $typecredit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typecredit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typecredit $typecredit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypecreditType::class, $typecredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_typecredit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typecredit/edit.html.twig', [
            'typecredit' => $typecredit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typecredit_delete', methods: ['POST'])]
    public function delete(Request $request, Typecredit $typecredit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typecredit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typecredit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_typecredit_index', [], Response::HTTP_SEE_OTHER);
    }
}
