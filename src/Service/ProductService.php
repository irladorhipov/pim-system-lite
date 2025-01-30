<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\Api\DTO\Product\ProductRequestDTO;
use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private EntityManagerInterface $em;
    private string $photosDirectory;

    public function __construct(EntityManagerInterface $em, string $photosDirectory)
    {
        $this->em = $em;
        $this->photosDirectory = $photosDirectory;
    }

    public function getProducts(): array
    {
        return $this->em->getRepository(Product::class)->findAll();
    }

    public function getProductById(int $id): Product
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function createProduct(ProductRequestDTO $dto): Product
    {
        $product = (new Product())
            ->setName($dto->getName())
            ->setPrice($dto->getPrice())
            ->setDescription($dto->getDescription());

        if ($dto->hasPhoto()) {
            $photo = $dto->getPhoto();
            $newFilename = sprintf('%s.%s', uniqid(), $photo->guessExtension());

            $photo->move($this->photosDirectory, $newFilename);
            $product->setPhoto($newFilename);
        }

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function updateProduct(int $id, ProductRequestDTO $dto): void
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        $product->setName($dto->getName());
        $product->setPrice($dto->getPrice());
        $product->setDescription($dto->getDescription());

        if ($dto->hasPhoto()) {
            $photo = $dto->getPhoto();
            $newFilename = sprintf('%s.%s', uniqid(), $photo->guessExtension());

            $photo->move($this->getParameter('photos_directory'), $newFilename);
            $product->setPhoto($newFilename);
        }

        $this->em->flush();
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        $this->em->remove($product);
        $this->em->flush();
    }
}
