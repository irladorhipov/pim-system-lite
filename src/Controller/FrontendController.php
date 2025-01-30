<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ProductType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{

    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }

    #[Route('/products/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return $this->render('product/new.html.twig');
    }

    #[Route('/products/{id}/edit', name: 'product_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        return $this->render('product/edit.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/products/{id}', name: 'product_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('product/show.html.twig', [
            'id' => $id,
        ]);
    }
}
