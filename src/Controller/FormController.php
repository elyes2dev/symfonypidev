<?php

namespace App\Controller;

use App\Entity\Form;
use App\Entity\Formquestions;
use App\Form\FormType;
use App\Repository\FormRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form')]
class FormController extends AbstractController
{
    #[Route('/', name: 'app_form_index', methods: ['GET'])]
    public function index(FormRepository $formRepository): Response
    {
        return $this->render('form/index.html.twig', [
            'forms' => $formRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_form_new')]
    public function neww(Request $request): Response
    {
        $form = new Form();
        $form->setCreationdate(new \DateTime()); // Set default creation date

        $formForm = $this->createForm(FormType::class, $form);
        $formForm->handleRequest($request);

        if ($formForm->isSubmitted() && $formForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form);
            $entityManager->flush();

            // return $this->redirectToRoute('app_form_show', ['id' => $form->getId()]);
            return $this->redirectToRoute('app_form_index');
        }

        return $this->render('form/new.html.twig', [
            'formForm' => $formForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_form_new')]
    public function new(Request $request, QuestionRepository $questionRepository): Response
    {
        $form = new Form();
        $form->setCreationdate(new \DateTime()); // Set default creation date

        // Add an empty form question
        $formQuestion = new Formquestions();
        $form->addFormquestion($formQuestion);

        // Retrieve all questions from the database
        $questions = $questionRepository->findAll();

        // Create the form with the questions as options
        $formForm = $this->createForm(FormType::class, $form, ['questions' => $questions]);
        $formForm->handleRequest($request);

        if ($formForm->isSubmitted() && $formForm->isValid()) {
            // Get the selected questions from the form
            $selectedQuestions = $formForm->get('formquestions')->getData();

            // Associate each selected question with the form
            foreach ($selectedQuestions as $selectedQuestion) {
                // Associate the question with the form through the Formquestions entity
                $formQuestion = new Formquestions();
                $formQuestion->setQuestion($selectedQuestion);
                $form->addFormquestion($formQuestion);
            }

            // Persist the form and its associated questions
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form);
            $entityManager->flush();

            return $this->redirectToRoute('form_show', ['id' => $form->getId()]);
        }

        return $this->render('form/new.html.twig', [
            'formForm' => $formForm->createView(),
        ]);
    }

//     #[Route('/new', name: 'app_form_new')]
// public function new(Request $request, QuestionRepository $questionRepository): Response
// {
//     $form = new Form();
//     $form->setCreationdate(new \DateTime()); // Set default creation date

//     // Retrieve all questions from the database
//     $questions = $questionRepository->findAll();

//     // Create the form with the questions as options
//     $formForm = $this->createForm(FormType::class, $form, ['questions' => $questions]);
//     $formForm->handleRequest($request);

//     if ($formForm->isSubmitted() && $formForm->isValid()) {
//         // Get the selected questions from the form
//         $selectedQuestions = $formForm->get('formquestions')->getData();

//         // Iterate through each selected question
//         foreach ($selectedQuestions as $selectedQuestion) {
//             // Create a new Formquestions entity
//             $formQuestion = new Formquestions();
            
//             // Set the question and form for the Formquestions entity
//             $formQuestion->setQuestion($selectedQuestion);
//             $formQuestion->setForm($form);
            
//             // Persist the Formquestions entity
//             $entityManager = $this->getDoctrine()->getManager();
//             $entityManager->persist($formQuestion);
//         }

//         // Persist the form
//         $entityManager->persist($form);
//         $entityManager->flush();

//         return $this->redirectToRoute('form_show', ['id' => $form->getId()]);
//     }

//     return $this->render('form/new.html.twig', [
//         'formForm' => $formForm->createView(),
//     ]);
// }


    #[Route('/{id}', name: 'app_form_show', methods: ['GET'])]
    public function show(Form $form): Response
    {
        return $this->render('form/show.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_form_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Form $form, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormType::class, $form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_form_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('form/edit.html.twig', [
            'form' => $form,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_form_delete', methods: ['POST'])]
    public function delete(Request $request, Form $form, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$form->getId(), $request->request->get('_token'))) {
            $entityManager->remove($form);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_form_index', [], Response::HTTP_SEE_OTHER);
    }
}
