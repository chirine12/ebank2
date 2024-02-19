<?php

namespace App\Controller;

use App\Entity\Compteep;
use App\Form\AdmincompteepType;
use App\Form\CompteepType;
use App\Repository\CompteepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Admincompteep')]
class AdmincompteepController extends AbstractController
{
    #[Route('/', name: 'Adminapp_compteep_index', methods: ['GET'])]
    public function index(CompteepRepository $compteepRepository): Response
    {
        

        
        $compteeps = $compteepRepository->findAll();

        return $this->render('Admincompteep/index.html.twig', [
            'compteeps' => $compteeps,
        ]);
    }
    
    #[Route('/new', name: 'Adminapp_compteep_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {    

        // Récupérer l'entité Client associée à l'utilisateur
       
        $compteep = new Compteep();

       $compteep->setSolde(0);
       $compteep->setEtat(1);
       $compteep->setDateOuv(new \DateTime());
        
        
        do {
            $Rib = mt_rand(10000000000, 99999999999);
        } while ($this->isRibUnique($Rib, $entityManager));
    
        $compteep->setRib($Rib);

        $form = $this->createForm(AdmincompteepType::class, $compteep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteep);
            $entityManager->flush();

            return $this->redirectToRoute('Adminapp_compteep_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admincompteep/new.html.twig', [
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
  

    #[Route('/{id}', name: 'Adminapp_compteep_show', methods: ['GET'])]
    public function show(Compteep $compteep): Response
    {
        return $this->render('Admincompteep/show.html.twig', [
            'compteep' => $compteep,
        ]);
    }
    #[Route('/{id}/edit', name: 'Adminapp_compteep_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Compteep $compteep, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdmincompteepType::class, $compteep, ['is_edit' => true]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('Adminapp_compteep_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Admincompteep/edit.html.twig', [
            'compteep' => $compteep,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'Adminapp_compteep_delete', methods: ['POST'])]
public function delete(Request $request, Compteep $compteep, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$compteep->getId(), $request->request->get('_token'))) {
        // Au lieu de supprimer, mettez à jour l'état du compte
        $compteep->setEtat(false); // "false" pour inactif, ajustez si nécessaire
        $entityManager->persist($compteep);
        $entityManager->flush();
    }

    return $this->redirectToRoute('Adminapp_compteep_index', [], Response::HTTP_SEE_OTHER);
}

}
