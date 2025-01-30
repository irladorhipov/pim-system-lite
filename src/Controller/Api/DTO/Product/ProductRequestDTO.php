<?php

declare(strict_types=1);

namespace App\Controller\Api\DTO\Product;

use App\Controller\Api\DTO\AbstractDTO;
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
        $this->setName($params['name'] ?? '');
        $this->setPrice(array_key_exists('price', $params)  ? (int)$params['price'] : 0);

        $file = $this->getRequest()->files->get('photo');

        if ($file instanceof File) {
            $this->setPhoto($file);
        }

        $this->setDescription($params['description'] ?? '');

        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPhoto(): ?File
    {
        return $this->photo;
    }

    public function setPhoto(File $file): static
    {
        $this->photo = $file;

        return $this;
    }

    public function hasPhoto(): bool
    {
        return null !== $this->photo;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
