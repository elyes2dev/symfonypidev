<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Offer; // Import the Offer entity
use Doctrine\ORM\EntityManagerInterface;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/checkout/{offerId}', name: 'checkout')]
    public function checkout($stripeSK, int $offerId, EntityManagerInterface $entityManager): Response
    {  
        // Fetch the selected offer from the database
        $offerRepository = $entityManager->getRepository(Offer::class);
        $offer = $offerRepository->find($offerId);
    
        // Check if the offer exists
        if (!$offer) {
            // Handle error: Offer not found
            // You can redirect the user to an error page or display a flash message
            return $this->redirectToRoute('your_error_route_here');
        }
    
        Stripe::setApiKey($stripeSK);
    
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $offer->getName(),
                            'description' => $offer->getText(), // Description of the offer
                                                ],
                                                'unit_amount'  => $offer->getPrice() * 100, // Convert price to cents (Stripe expects amount in smallest currency unit)
                                            ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('app_subscription_create', ['offerId' => $offerId], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
