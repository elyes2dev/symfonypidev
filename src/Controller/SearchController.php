<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_product_search')]
    public function searchProduct(Request $request, ProductRepository $repository): Response
    {
        $query = $request->request->get('query');
        $products = $repository->searchByName($query);
        return $this->render('product/search.html.twig', [
            'products' => $products
        ]);
    }

    
}
