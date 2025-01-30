<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Integration\Parser;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class ParserTest extends TestCase
{
    private Parser $parser;
    private HttpClientInterface $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->parser = new Parser($this->httpClient);
    }

    public function testParseProductInfo(): void
    {
        $htmlContent = '
            <html>
                <body>
                    <h1 class="product-title">Test Product</h1>
                    <div class="price">1 234,56 ₽</div>
                    <div class="product-image">
                        <img src="https://example.com/image.jpg" alt="Product Image">
                    </div>
                </body>
            </html>
        ';

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($htmlContent);
        $this->httpClient->method('request')->willReturn($response);

        $productInfo = $this->parser->parseProductInfo('https://alza.cz/test-product');

        $this->assertEquals('Test Product', $productInfo['name']);
        $this->assertEquals(1234.56, $productInfo['price']);
        $this->assertEquals('https://test.com/image.jpg', $productInfo['photo']);
    }
}
