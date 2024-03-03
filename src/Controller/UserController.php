<?php
namespace App\Controller;


use App\Entity\User;
use App\Entity\Virement;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user')]
class UserController extends AbstractController
{
   #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
   public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
   {
       $form = $this->createForm(UserType::class, $user);
       $form->handleRequest($request);


       if ($form->isSubmitted() && $form->isValid()) {
           // Handle form submission and updating the user entity
           $entityManager->flush();


           return $this->redirectToRoute('app_admininterface');
       }


       return $this->render('user/edit.html.twig', [
           'user' => $user,
           'form' => $form->createView(),
       ]);
   }


   #[Route('/user/delete/{id}', name: 'app_user_delete', methods: ['GET', 'POST'])]
public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
   $userId = $user->getId();


   // Check if there are related records in the virement table
   $virements = $entityManager->getRepository(Virement::class)->findBy(['client' => $user->getClient()]);


   // If there are related records, handle them first
   foreach ($virements as $virement) {
       $entityManager->remove($virement);
   }


   // Now you can safely delete the user
   $entityManager->remove($user);
   $entityManager->flush();


   return $this->redirectToRoute('app_admininterface', [], Response::HTTP_SEE_OTHER);
}


  


}


