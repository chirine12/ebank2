<?php

namespace App\Controller;
use App\Entity\comptecourant;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $client = new Client();
    $form = $this->createForm(ClientType::class, $client);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Check if the user is authenticated
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Handle the case where the user is not authenticated
            return $this->redirectToRoute('app_login'); // Redirect to the login page
            // Or return an error response
            // return new Response('User not authenticated', Response::HTTP_UNAUTHORIZED);
        }

        // The user is authenticated
        $user = $this->getUser();
        $client->setUser($user);

        $account = new Comptecourant();
        $account->setDateouv(new \DateTime());
        $account->setRib("00000000");
        $account->setId($client->getid());
        $account->setSolde("999999999");
        $client->setComptecourant($account);

        $entityManager->persist($client);
        $entityManager->persist($account);
        $entityManager->flush();

        return $this->redirectToRoute('app_userinterface', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('client/new.html.twig', [
        'client' => $client,
        'form' => $form,
    ]);
}
    


    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/client/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission and updating the client entity
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
        
}
