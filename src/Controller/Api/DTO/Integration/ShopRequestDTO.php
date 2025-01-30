<?php
declare(strict_types=1);

namespace App\Controller\DTO\Integration;

use App\Controller\DTO\AbstractDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ShopRequestDTO extends AbstractDTO
{
    #[Assert\NotBlank]
    private string $url;

    public function processRequest(array $params = []): void
    {
        $this->setUrl($params['url'] ?? '');
        $this->validate();
    }

    public function getUrl(): string
    {
       return $this->url;
    }

    public function setUrl(string $url): static
    {
         $this->url = $url;

         return $this;
    }
}