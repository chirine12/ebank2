<?php
// src/Service/BinanceService.php

namespace App\Service;

use Binance\API;

class BinanceService
{
    private $api;

    // Constructor to initialize the Binance API with provided API key and API secret
    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->api = new API($apiKey, $apiSecret);
    }

    // Function to fetch the current Bitcoin price from the Binance API
    public function getBitcoinPrice(): ?float
    {
        try {
            // Fetch the current Bitcoin price from Binance API
            $ticker = $this->api->prices('BTCUSDT');
            $bitcoinPrice = (float) $ticker['BTCUSDT'];
            return $bitcoinPrice;
        } catch (\Exception $e) {
            // Log or handle the exception
            return null;
        }
    }

    // Function to place a buy order for cryptocurrency on Binance
    public function buyCrypto(string $symbol, float $quantity, float $price): array
    {
        try {
            // Place a buy order using Binance API
            $response = $this->api->marketBuy($symbol, $quantity, $price, []);
            return $response;
        } catch (\Exception $e) {
            // Log or handle the exception
            return [];
        }
    }

    // Function to place a sell order for cryptocurrency on Binance
    public function sellCrypto(string $symbol, float $quantity, float $price): array
    {
        try {
            // Place a sell order using Binance API
            $response = $this->api->marketSell($symbol, $quantity, $price, ['type' => 'LIMIT']);
            return $response;
        } catch (\Exception $e) {
            // Log or handle the exception
            return [];
        }
    }
}
