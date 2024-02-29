<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client as GuzzleClient; // Alias GuzzleHttp\Client as GuzzleClient

use App\Entity\Client;
use App\Entity\Invest;
use App\Entity\Comptecourant;

class UserinterfaceController extends AbstractController
{

    #[Route('/update-solde', name: 'update-solde', methods: ['POST'])]
    public function updatesolde(Request $request): JsonResponse
    {
        $clientId = $request->request->get('clientId');
        $amount = $request->request->get('amount');
    
        // Fetch the Comptecourant entity based on the $clientId
        $entityManager = $this->getDoctrine()->getManager();
    
        // Use a custom query to find the client by Comptecourant
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Client::class, 'c');
        $rsm->addFieldResult('c', 'id', 'id');
        $rsm->addFieldResult('c', 'name', 'name');
        // Add other fields as needed
    
        $query = $entityManager->createNativeQuery('
            SELECT c.id, c.nom
            FROM client c
            JOIN comptecourant cc ON c.comptecourant_id = cc.id
            WHERE cc.id = :comptecourantId
        ', $rsm);
    
        $query->setParameter('comptecourantId', $clientId);
    
        $client = $query->getOneOrNullResult();
    
        if ($client) {
            // Client found, proceed with decrementing solde
            $comptecourant = $client->getComptecourant();
    
            if ($comptecourant) {
                // Decrement the solde by the specified amount
                $currentSolde = $comptecourant->getSolde();
                $newSolde = $currentSolde - $amount;
    
                // Update the solde in the Comptecourant entity
                $comptecourant->setSolde($newSolde);
    
                // Persist the changes to the database
                $entityManager->flush();
            }
    
            return new JsonResponse(['success' => true]);
        }
    
        // Client not found
        return new JsonResponse(['success' => false, 'message' => 'Client not found'], 404);
    }
    #[Route('/decrement-solde', name: 'decrement-solde', methods: ['POST','GET'])]
    public function decrementsolde(Request $request): JsonResponse
    {
        // Retrieve the clientId and amount from the request
    $clientId = $request->request->get('clientId');
    $amount = $request->request->get('amount');

    // Fetch the Comptecourant entity based on the $clientId
    $entityManager = $this->getDoctrine()->getManager();

    // Use a custom query to find the client by Comptecourant
    $rsm = new ResultSetMapping();
    $rsm->addEntityResult(Client::class, 'c');
    $rsm->addFieldResult('c', 'id', 'id');
    $rsm->addFieldResult('c', 'name', 'name');
    // Add other fields as needed

    $query = $entityManager->createNativeQuery('
        SELECT c.id, c.nom
        FROM client c
        JOIN comptecourant cc ON c.comptecourant_id = cc.id
        WHERE cc.id = :comptecourantId
    ', $rsm);

    $query->setParameter('comptecourantId', $clientId);

    $client = $query->getOneOrNullResult();

    if ($client) {
        // Client found, proceed with decrementing solde
        $comptecourant = $client->getComptecourant();

        if ($comptecourant) {
            // Decrement the solde by the specified amount
            $currentSolde = $comptecourant->getSolde();
            $newSolde = $currentSolde - $amount;

            // Update the solde in the Comptecourant entity
            $comptecourant->setSolde($newSolde);

            // Persist the changes to the database
            $entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }

    // Client not found
    return new JsonResponse(['success' => false, 'message' => 'Client not found'], 404);
    }

    #[Route('/process-transaction', name: 'app_process_transaction', methods: ['POST'])]
    public function processTransaction(Request $request): JsonResponse
    {
        // Your existing implementation here

        return $this->json(['success' => true]);
    }

    // Your existing code for getClientInvestments and index methods...



    // Mock function to simulate balance update
    private function calculateUpdatedBalance($amount, $action)
    {
        // Implement your logic to update the balance based on the action (buy/sell)
        // This is a mock example; replace it with your actual logic

        $mainBalance = $this->getMainBalance();  // Fetch the actual main balance
        if ($action === 'buy') {
            $updatedBalance = $mainBalance - $amount;
        } else {
            $updatedBalance = $mainBalance + $amount;
        }

        return $updatedBalance;
    }

    // Mock function to simulate fetching the main balance
    private function getMainBalance()
    {
        // Replace this with your logic to fetch the main balance from your data source
        // For demonstration, using a fixed value
        return 1000;
    }

    private function getClientInvestments(int $clientId): array
{
    $investments = $this->getDoctrine()->getRepository(Invest::class)->findBy(['idclient' => $clientId]);
    $netAmount = 0;
    $investmentsSummary = [];

    // Create an associative array to store the total amounts for each cryptocurrency
    $totalAmounts = [];

    foreach ($investments as $investment) {
        $amount = $investment->getAmount();
        $crypto = $investment->getCrypto();

        if (!isset($totalAmounts[$crypto])) {
            $totalAmounts[$crypto] = 0;
        }

        if ($investment->getAction() == 'buy') {
            $netAmount += $amount;
            $totalAmounts[$crypto] += $amount;
        } elseif ($investment->getAction() == 'sell') {
            $netAmount -= $amount;
            $totalAmounts[$crypto] -= $amount;
        }
    }

    // Convert totalAmounts into an array of strings for summary
    foreach ($totalAmounts as $crypto => $totalAmount) {
        $investmentsSummary[] = sprintf("Vous avez investi %s TND en %s", $totalAmount, $crypto);
    }

    // The netAmount variable will contain the total gains/losses
    return [
        'netAmount' => $netAmount,
        'investmentsSummary' => $investmentsSummary,
    ];
}

    
#[Route('/userinterface', name: 'app_userinterface')]
public function index(): Response
{
    $user = $this->getUser();
    $email = $user->getUsername();
    $roles = $user->getRoles();
    $name = $user->getname();
    if ($roles[0] === 'ROLE_USER') {
        $role = 'Compte Basic';
    } else {
        $role = 'Unknown Role';
    }

    $entityManager = $this->getDoctrine()->getManager();
    $comptecourantId = 0;
    $client = $this->getUser()->getClient();
    
    if ($client) {
        $comptecourant = $client->getComptecourant();
    
        if ($comptecourant) {
            $comptecourantId = $comptecourant->getId();
        }
    }
    
    $comptecourant = $entityManager->getRepository(Comptecourant::class)->find($comptecourantId);
    $mainBalance = ($comptecourant instanceof Comptecourant) ? $comptecourant->getSolde() : 0;

    $cryptoGain = $this->getClientInvestments($user->getId());
    $recommendations = $this->getChatGPTRecommendations($cryptoGain);

    // Mock payment, weeklySummary, and invoice data
$paymentsData = [
            [
                'avatar' => 'images/avatar/1.jpg',
                'userName' => 'Livia Bator',
                'shopName' => 'Online Shop',
                'timestamp' => 'June 5, 2020, 08:22 AM',
                'amount' => '+$5,553',
                'paymentMethod' => 'MasterCard 404',
                'status' => 'Pending',
                'paymentId' => '#00123521',
                'invoiceDate' => 'April 29, 2020',
                'dueDate' => 'June 5, 2020',
                'datePaid' => 'June 4, 2020',
                'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            // Add more payment objects as needed
        ];
     $weeklySummaryData = [
            'income' => 99,
            'expense' => 99,
            'unknown' => 99,
            'columnChartData' => [10, 20, 15, 30, 25, 35, 40], // Sample data for the column chart
        ];

        // Mock invoice data
        $invoicesData = [
            [
                'profilePic' => 'images/profile/small/pic1.jpg',
                'clientName' => 'Dedi Cahyadi',
                'clientRole' => 'Head Manager',
                'profileLink' => '/pro',
                'invoiceAmount' => '$776',
            ],
            // Add more invoice objects as needed
        ];


    return $this->render('userinterface/index.html.twig', [
        'email' => $email,
        'user' => $user,
        'name' => $name,
        'Main_Balance' => $mainBalance,
        'role' => $role,
        'Investment' => 999,
        'paymentsData' => $paymentsData,
        'weeklySummaryData' => $weeklySummaryData,
        'cryptoGain' => $cryptoGain,
        'invoicesData' => $invoicesData,
        'chatGPTRecommendations' => $recommendations,
    ]);
}

private $httpClient;

public function __construct(HttpClientInterface $httpClient)
{
    $this->httpClient = $httpClient;
}

private function getChatGPTRecommendations($cryptoGain): array
{
    $apiUrl = 'https://api.openai.com/v1/chat/completions';

    // Prepare headers with the API key
    $headers = [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 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',
        ],
        'body' => json_encode(['cryptoGain' => $cryptoGain]),
    ];

    try {
        // Make a POST request to ChatGPT API using Symfony HttpClient
        $response = $this->httpClient->request('POST', $apiUrl, $headers);

        // Decode the JSON response
        $recommendations = $response->toArray();

        return $recommendations;
    } catch (\Exception $e) {
        // Handle exceptions (e.g., if the request fails)
        return ['error' => $e->getMessage()];
    }
}
}
