<?php

namespace App\Service;

use Symfony\Component\Panther\Client;
use Symfony\Component\DomCrawler\Crawler;

class AlzaParserService
{
    private $client;

    public function parseProductInfo(string $url): array
    {
        $client = Client::createSeleniumClient('http://localhost:4444/wd/hub/session');
        $crawler = $client->request('GET', $url);
        $title = $crawler->filter('h1')->text();

        $price = $crawler->filter('span.price-box__price')->text();
        $price = preg_replace('/[^0-9]/', '', $price);

        $image = $crawler->filter('img.gallery__image')->attr('src');

        return [
            'title' => $title,
            'price' => (int)$price,
            'image' => $image,
        ];
    }
}