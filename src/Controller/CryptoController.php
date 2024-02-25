<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;  // Add this line
use Symfony\Component\Routing\Annotation\Route;

class CryptoController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/crypto", name="crypto")
     */
    public function index(): Response
    {
        // Získáme aktuální kurz kryptoměny Bitcoin v EUR
        $response_eur = $this->httpClient->request('GET', 'https://api.coindesk.com/v1/bpi/currentprice/EUR.json');
        $data_eur = $response_eur->toArray();

        // Získáme aktuální kurz kryptoměny Bitcoin v USD
        $response_usd = $this->httpClient->request('GET', 'https://api.coindesk.com/v1/bpi/currentprice/USD.json');
        $data_usd = $response_usd->toArray();

        // Vytvoříme pole s daty
        $data = [
            'price_eur' => number_format($data_eur['bpi']['EUR']['rate_float'], 2, ',', ' '),
            'price_usd' => number_format($data_usd['bpi']['USD']['rate_float'], 2, '.', ','),
        ];

        // Vykreslíme šablonu a předáme jí data
        return $this->render('crypto/index.html.twig', [
            'data' => $data,
        ]);
    }
}
