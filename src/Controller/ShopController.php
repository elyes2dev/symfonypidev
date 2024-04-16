<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shop')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'app_shop_index', methods: ['GET'])]
    #[Route('/product/{id}', name: 'app_shop_product_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function index(ProductRepository $productRepository, Product $product = null): Response
    {
        if ($product) {
            return $this->render('shop/show.html.twig', [
                'product' => $product,
            ]);
        }

        $products = $productRepository->findAll();

        return $this->render('shop/shop.html.twig', [
            'products' => $products,
        ]);
    }
}

