<?php

namespace App\Controller;

use App\Entity\Product; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;

class TriController extends AbstractController
{
    #[Route('/tri', name: 'app_tri')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll(); // Fetch all products
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_id_asc', name: 'sorted_by_id_asc')]
    public function displaySortedByIdASC(ProductRepository $productRepository): Response
    {
        
        $products = $productRepository->findBy([], ['id' => 'ASC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_type_asc', name: 'sorted_by_type_asc')]
    public function displaySortedByTypeASC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['type' => 'ASC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_name_asc', name: 'sorted_by_name_asc')]
    public function displaySortedByNameASC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['name' => 'ASC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_reference_asc', name: 'sorted_by_reference_asc')]
    public function displaySortedByReferenceASC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['reference' => 'ASC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_price_asc', name: 'sorted_by_price_asc')]
    public function displaySortedByPriceASC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['price' => 'ASC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_id_desc', name: 'sorted_by_id_desc')]
    public function displaySortedByIdDESC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['id' => 'DESC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_type_desc', name: 'sorted_by_type_desc')]
    public function displaySortedByTypeDESC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['type' => 'DESC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_name_desc', name: 'sorted_by_name_desc')]
    public function displaySortedByNameDESC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['name' => 'DESC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_reference_desc', name: 'sorted_by_reference_desc')]
    public function displaySortedByReferenceDESC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['reference' => 'DESC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/tri/sorted_by_price_desc', name: 'sorted_by_price_desc')]
    public function displaySortedByPriceDESC(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['price' => 'DESC']);
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

}
