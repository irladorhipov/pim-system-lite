<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\DomCrawler\Crawler;

class ParserService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function parseProductInfo(string $url): array
    {
        try {
            $response = $this->client->request('GET', $url);

            $htmlContent = $response->getContent();

            $crawler = new Crawler($htmlContent);

            $name = $crawler->filter('h1.product-title')->text();
            $price = $crawler->filter('.price')->text();
            $imageUrl = $crawler->filter('.product-image img')->attr('src');

            return [
                'name' => trim($name),
                'price' => (float) preg_replace('/[^\d.]/', '', $price),
                'photo' => $imageUrl,
            ];
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Failed to fetch product information: ' . $e->getMessage());
        }
    }
}
