<?php

declare(strict_types=1);

namespace App\Controller\DTO\Product;

use App\Controller\DTO\AbstractDTO;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ProductRequestDTO extends AbstractDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $price;

    #[Assert\Length(max: 255)]
    private ?File $photo = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 1000)]
    private string $description;

    public function processRequest(array $params = []): void
    {
        $this->name = $params['name'] ?? '';
        $this->price = array_key_exists('price', $params)  ? (int)$params['price'] : 0;
        $this->photo = $this->getRequest()->files->get('photo') ?? null;
        $this->description = $params['description'] ?? '';


        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getPhoto(): ?File
    {
        return $this->photo;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasPhoto(): bool
    {
        return null !== $this->photo;
    }
}
