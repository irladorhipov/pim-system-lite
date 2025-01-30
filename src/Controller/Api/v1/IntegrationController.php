<?php

namespace App\Controller\Api\v1;

use App\Controller\Api\DTO\Integration\ShopRequestDTO;
use App\Service\ParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationController extends AbstractController
{
    private ParserService $parserService;

    public function __construct(ParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    #[Route('api/parser/product', name: 'product_show', methods: ['POST'])]

    public function getProductInfoByUrl(ShopRequestDTO $dto): JsonResponse
    {
       $data = $this->parserService->parseProductInfo($dto->getUrl());

       return $this->json($data, Response::HTTP_OK);
    }
}