<?php

namespace App\Controller;
use App\Entity\client;
use App\Entity\Credit;
use App\Entity\Typecredit;
use App\Form\CreditType;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;


#[Route('/credit')]
class CreditController extends AbstractController
{
    #[Route('/', name: 'app_credit_index', methods: ['GET'])]
    public function index(CreditRepository $creditRepository): Response
    {
        return $this->render('credit/index.html.twig', [
            'credits' => $creditRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);
       
       

         if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le type de crédit choisi dans le formulaire
            $typeCredit = $credit->getType();
           

            // Affecter le type choisi à la propriété typeC de l'objet Credit
            switch ($typeCredit) {
                case 'Immobilier':
                    $dureeCredit = 10; // DurÃ©e en annÃ©es pour un crÃ©dit immobilier
                    break;
                case 'voiture':
                    $dureeCredit = 5; // DurÃ©e en annÃ©es pour un crÃ©dit automobile
                    break;
                
                default:
                    throw new \InvalidArgumentException('Type de crÃ©dit non pris en charge.');}
                    $credit->setDuree($dureeCredit);

            // Formules de calcules: 
                // Formule 1 : Payment
                  $payment = $credit->getMontant()/(12*$dureeCredit) ;
                  $credit->setPayement($payment);    


            // Enregistrer le crédit
            $entityManager->persist($credit);
            $entityManager->flush();

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }else {
            // Render the form again with validation errors
            $this->addFlash('error', 'Please fill out all required fields.');
            return $this->render('credit/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->renderForm('credit/new.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_show', methods: ['GET'])]
    public function show(Credit $credit): Response
    {
        return $this->render('credit/show.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credit $credit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $typeCredit = $credit->getType();
            switch ($typeCredit) {
                case 'Immobilier':
                    $dureeCredit = 10; // Durée en années pour un crédit immobilier
                    break;
                case 'voiture':
                    $dureeCredit = 5; // Durée en années pour un crédit automobile
                    break;
                
                default:
                    throw new \InvalidArgumentException('Type de crédit non pris en charge.');
            }
            $credit->setDuree($dureeCredit);

            $entityManager->flush();
            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/edit.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_delete', methods: ['POST'])]
    public function delete(Request $request, Credit $credit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$credit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($credit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
    }

  }