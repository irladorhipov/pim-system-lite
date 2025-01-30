<?php

namespace App\Controller;

use App\Controller\DTO\Integration\ShopRequestDTO;
use App\Service\ParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IntegrationController extends AbstractController
{
    private ParserService $parserService;

    public function __construct(ParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    public function getProductInfoByUrl(ShopRequestDTO $dto): JsonResponse
    {
       $data = $this->parserService->parseProductInfo($dto->getUrl());

       return $this->json($data, Response::HTTP_OK);
    }
}