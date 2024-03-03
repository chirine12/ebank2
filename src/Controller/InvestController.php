<?php

namespace App\Controller;

use App\Entity\Invest;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\InvestType;
use Symfony\Component\HttpClient\HttpClient;

use App\Repository\InvestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/invest')]
class InvestController extends AbstractController
{
    #[Route('/', name: 'app_invest_index', methods: ['GET'])]
    public function index(InvestRepository $investRepository): Response
    {
        return $this->render('invest/index.html.twig', [
            'invests' => $investRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invest = new Invest();
    
        $user = $this->getUser();
        $client = $user->getClient();
        $compteCourant = $client->getCompteCourant();
        $compteCourantSolde = $compteCourant->getSolde();
        $invest->setIdclient($client->getId());
    
        // Use $this->getBTCPrice() to call the function
        $btcPrice = $this->getBTCPrice();
    
        $form = $this->createForm(InvestType::class, $invest);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve amount, crypto, and action from the form
            $amount = $form->get('amount')->getData();
            $crypto = $form->get('crypto')->getData();
            $action = $form->get('action')->getData();
    
            // Ensure the balance is sufficient for a buy action
            if ($action === 'buy' && $compteCourantSolde < $amount) {
                // Handle insufficient balance for a buy action
                $this->addFlash('error', 'Insufficient balance for a buy action.');
    
                return $this->redirectToRoute('app_invest_index');
            }
    
            // Perform the calculation based on the BTC price
            $amountBtc = (float)(($amount / 3.12) / $btcPrice);
    
            // Update the balance based on the action
            if ($action === 'buy') {
                $newSolde = $compteCourantSolde - $amount;
            } elseif ($action === 'sell') {
                $newSolde = $compteCourantSolde + $amount;
            } else {
                // Handle unsupported action, you might want to add a flash message
                $this->addFlash('error', 'Unsupported action.');
    
                return $this->redirectToRoute('app_invest_index');
            }
    
            // Update the balance
            $compteCourant->setSolde($newSolde);
            $invest->setBtcpriceinaction($btcPrice);
            $invest->setAmount((float)$amountBtc);
    
            // Persist the entity
            $entityManager->persist($invest);
    
            // Flush to save changes to the database
            $entityManager->flush();
    
            // Redirect to the index page
            return $this->redirectToRoute('app_invest_index');
        }
    
        return $this->renderForm('invest/new.html.twig', [
            'invest' => $invest,
            'form' => $form,
        ]);
    }
    
    
    


    #[Route('/{id}', name: 'app_invest_show', methods: ['GET'])]
    public function show(Invest $invest): Response
    {
        return $this->render('invest/show.html.twig', [
            'invest' => $invest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invest_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invest $invest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvestType::class, $invest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invest/edit.html.twig', [
            'invest' => $invest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invest_delete', methods: ['POST'])]
    public function delete(Request $request, Invest $invest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invest->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invest_index', [], Response::HTTP_SEE_OTHER);
    }



    function getBTCPrice() {
        $apiUrl = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd';
    
        // Use Symfony's HttpClient for the API request
        $client = HttpClient::create();
        $response = $client->request('GET', $apiUrl);
    
        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            // Decode the JSON response
            $data = $response->toArray();
    
            // Check if the decoding was successful and the necessary data is present
            if (!empty($data['bitcoin']['usd'])) {
                // Get the BTC price in USD
                return (float)$data['bitcoin']['usd'];
            }
        }
    
        // If the request failed or the data is not present, return null or handle the error accordingly
        return null;
    }
    
    
    


 
    



}
