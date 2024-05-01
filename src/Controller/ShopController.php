<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/shop')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'app_shop_index', methods: ['GET'])]
    #[Route('/product/{id}', name: 'app_shop_product_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function index(ProductRepository $productRepository, Product $product = null,Request $request, PaginatorInterface $paginator): Response
    {
        if ($product) {
            return $this->render('shop/show.html.twig', [
                'product' => $product,
            ]);
        }
        $product = $paginator->paginate(
            $product=$productRepository->findAll(),
            $page = $request->query->getInt('page', 1),
            5
        );

        $products = $product;

        return $this->render('shop/shop.html.twig', [
            'products' => $products,
        ]);
    }

    /**
 * @Route("/search", name="search", methods={"GET"})
 */
/**
 * @Route("/add-to-cart", name="add_to_cart", methods={"POST"})
 */
#[Route('/add_to_cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request): Response
    {
        // Retrieve the product ID from the request
        $productId = $request->request->get('productId');

        // Retrieve the product entity based on the product ID
        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);

        // Check if the product exists
        if (!$product) {
            return $this->json(['success' => false, 'message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        // Create a new Cart entity
        $cartItem = new Cart();
        $cartItem->setProduct($product);

        // Persist the Cart entity
        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();

        // Return a JSON response indicating success
        return $this->json(['success' => true]);
    }

    
}

