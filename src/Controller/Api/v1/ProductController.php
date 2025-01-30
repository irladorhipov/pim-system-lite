<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Controller\Api\DTO\Product\ProductRequestDTO;
use App\Exception\ProductNotFoundException;
use App\Service\ProductService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductService $productService;
    private LoggerInterface $logger;

    public function __construct(ProductService  $productService,
                                LoggerInterface $logger)
    {
        $this->productService = $productService;
        $this->logger = $logger;
    }

    #[Route('api/products', name: 'api_product_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            $products = $this->productService->getProducts();

            return $this->json($products, Response::HTTP_OK);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Error get all products: %s', $e->getMessage()));

            return $this->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('api/products', name: 'api_product_new', methods: ['POST'])]
    public function new(ProductRequestDTO $dto): JsonResponse
    {
        try {
            if ($errors = $dto->getErrors()) {
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->productService->createProduct($dto);

            return $this->json(['message' => 'Product created successfully'], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            $this->logger->error('Error creating product: ' . $e->getMessage());

            return $this->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('api/product/{id}/show', name: 'api_product_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        try {
            $product = $this->productService->getProductById($id);

            return $this->json($product, Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Error deleting product: %s', $e->getMessage()));

            return $this->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('api/products/{id}', name: 'api_product_edit', methods: ['PUT'])]
    public function edit(ProductRequestDTO $dto, int $id): JsonResponse
    {
        try {
            if ($errors = $dto->getErrors()) {
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->productService->updateProduct($id, $dto);

            return $this->json(['message' => 'Product updated successfully'], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Error updating product: %s', $e->getMessage()));

            return $this->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('api/product/{id}/delete', name: 'api_product_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);

            return $this->json(['message' => 'Product deleted successfully'], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Error deleting product: %s', $e->getMessage()));

            return $this->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
