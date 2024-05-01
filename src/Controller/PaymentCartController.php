<?php

namespace App\Controller;

use App\Entity\PaymentCart;
use App\Form\PaymentCartType;
use App\Repository\PaymentCartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment/cart')]
class PaymentCartController extends AbstractController
{
    #[Route('/', name: 'app_payment_cart_index', methods: ['GET'])]
    public function index(PaymentCartRepository $paymentCartRepository): Response
    {
        return $this->render('payment_cart/index.html.twig', [
            'payment_carts' => $paymentCartRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_payment_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paymentCart = new PaymentCart();
        $form = $this->createForm(PaymentCartType::class, $paymentCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paymentCart);
            $entityManager->flush();

            return $this->redirectToRoute('app_payment_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_cart/new.html.twig', [
            'payment_cart' => $paymentCart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_cart_show', methods: ['GET'])]
    public function show(PaymentCart $paymentCart): Response
    {
        return $this->render('payment_cart/show.html.twig', [
            'payment_cart' => $paymentCart,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentCart $paymentCart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentCartType::class, $paymentCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payment_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_cart/edit.html.twig', [
            'payment_cart' => $paymentCart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_cart_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentCart $paymentCart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentCart->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paymentCart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payment_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
