<?php

namespace App\Controller;

use App\Entity\Formquestions;
use App\Form\FormquestionsType;
use App\Repository\FormquestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formquestions')]
class FormquestionsController extends AbstractController
{
    #[Route('/', name: 'app_formquestions_index', methods: ['GET'])]
    public function index(FormquestionsRepository $formquestionsRepository): Response
    {
        return $this->render('formquestions/index.html.twig', [
            'formquestions' => $formquestionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formquestions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formquestion = new Formquestions();
        $form = $this->createForm(FormquestionsType::class, $formquestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formquestion);
            $entityManager->flush();

            return $this->redirectToRoute('app_formquestions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formquestions/new.html.twig', [
            'formquestion' => $formquestion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formquestions_show', methods: ['GET'])]
    public function show(Formquestions $formquestion): Response
    {
        return $this->render('formquestions/show.html.twig', [
            'formquestion' => $formquestion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formquestions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formquestions $formquestion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormquestionsType::class, $formquestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formquestions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formquestions/edit.html.twig', [
            'formquestion' => $formquestion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formquestions_delete', methods: ['POST'])]
    public function delete(Request $request, Formquestions $formquestion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formquestion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formquestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formquestions_index', [], Response::HTTP_SEE_OTHER);
    }
}
