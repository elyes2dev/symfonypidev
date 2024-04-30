<?php

namespace App\Controller;

use App\Entity\Claim;
use App\Entity\User;
use App\Form\ClaimType;
use App\Form\ResponseType;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Services\MailerService;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use App\Entity\Notification;
use App\Repository\NotificationRepository;


    


use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/')]
class ClaimController extends AbstractController
{
   
    #[Route('/claim', name: 'app_claim_index', methods: ['GET'])]
    public function index(Request $request, ClaimRepository $claimRepository, NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'ID de l'utilisateur actuellement connecté
        $user = $entityManager->getRepository(User::class)->find(1);
    
        $type = $request->query->get('type');
    
        $claims = [];
        // Récupérer toutes les réclamations ou filtrer par type si spécifié
        if ($type && in_array($type, ['reservation', 'store', 'site'])) {
            $claims = $claimRepository->findBy(['type' => $type]);
        } else {
            $claims = $claimRepository->findAll();
        } 
    
        // Récupérer les notifications de l'utilisateur actuellement connecté
        $notifications = $notificationRepository->findBy(['iduser' => $user->getId()]);
    
        return $this->render('claim/index.html.twig', [
            'claims' => $claims,
            'notifications' => $notifications,
        ]);
    }
    

    #[Route('/claims', name: 'app_claim_claims', methods: ['GET'])]
    public function claims(ClaimRepository $claimRepository): Response
    {
        // Ajoutez l'ID de l'utilisateur statiquement (2 pour admin, 3 pour fieldowner)
        $userId = 3; // ou 3 selon votre besoin

        $claims = [];
    

        // Utilisez l'ID de l'utilisateur statiquement pour récupérer les réclamations
        if ($userId === 3) {
            // Utilisateur admin, récupérer les réclamations de type "site" et "store"
            $claims = $claimRepository->findByType('site');
            // Ajoutez les réclamations de type "store" s'il y en a
            $claims = array_merge($claims, $claimRepository->findByType('store'));
        } elseif ($userId === 5) {
            // Utilisateur fieldowner, récupérer les réclamations de type "réservations"
            $claims = $claimRepository->findByType('reservation');
        }
        
        return $this->render('claim/table.html.twig', [
            'claims' => $claims,
           
        ]);
    }

    #[Route('/chart', name: 'chart_page')]
public function showChartPage(ClaimRepository $claimRepository): Response
{
    // Récupérer les données des réclamations groupées par type depuis le repository
    $claimsByType = $claimRepository->countClaimsByType();

    // Passer les données au template
    return $this->render('claim/chart.html.twig', [
        'claims' => $claimsByType,
    ]);
}


   

 
   
    #[Route('/new', name: 'app_claim_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $claim = new Claim();
        $form = $this->createForm(ClaimType::class, $claim);
          
        // Retrieve the user with ID 2 from the database
        $user = $entityManager->getRepository(User::class)->find(1);
    
        // If the user with ID 2 does not exist, handle the error appropriately
        if (!$user) {
            throw $this->createNotFoundException('User with ID 1 not found');
        }
    
        // Set the user for the claim
        $claim->setIduser($user);
    
        // Set the system date
        $claim->setDate(new \DateTime());
    
        // Set the status
        $claim->setStatus('ON HOLD');
    
        // Set the satisfaction
        $claim->setSatisfaction('Not yet rated');
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the image file from the form data
            $file = $form->get('image')->getData();
            if ($file) {
                // Generate a unique name for the file before saving it
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
    
                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir') . '/public/Images',
                        $newFilename
                    );
                    // Update the imageUrl property of the claim entity
                    $claim->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the image.');
                    return $this->redirectToRoute('app_claim_index');
                }
            }
    
            // Persist the claim in the database
            $entityManager->persist($claim);
            $entityManager->flush();
    
            // Send email if $recette is not null
             // Replace with the appropriate method to get the recipe/entity
            if ($claim !== null) {
                // Set up mailer with Gmail transport
                $mailerDsn = 'gmail://saropez.pro@gmail.com:acex%20kpsf%20vzmn%20bzeu@default';
                $transport = Transport::fromDsn($mailerDsn);
                $mailer = new Mailer($transport);
                $mailerService = new MailerService($mailer); 
                $email = 'matchmate17@gmail.com';
                $mailerService->sendEmail($claim, $email);
            }
    
            // Add flash message for success
            $this->addFlash('success', 'Claim created successfully.');
    
            // Redirect to the claim index page
            return $this->redirectToRoute('app_claim_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('claim/new.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_claim_show', methods: ['GET'])]
    public function show(Claim $claim): Response
    {
        return $this->render('claim/show.html.twig', [
            'claim' => $claim,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_claim_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_claim_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('claim/edit.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }

   

    #[Route('/{id}', name: 'app_claim_delete', methods: ['POST'])]
    public function delete(Request $request, Claim $claim, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if ($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_FIELD_OWNER')) {
            // Rediriger vers la liste des réclamations
            $redirectRoute = 'app_claim_claims';
        } else {
            // Rediriger vers l'index des réclamations
            $redirectRoute = 'app_claim_index';
        }
    
        // Supprimer la réclamation si le jeton CSRF est valide
        if ($this->isCsrfTokenValid('delete'.$claim->getId(), $request->request->get('_token'))) {
            $entityManager->remove($claim);
            $entityManager->flush();
        }
    
        // Rediriger vers la route appropriée
        return $this->redirectToRoute($redirectRoute, [], Response::HTTP_SEE_OTHER);
    }
    
    
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }


    
    #[Route('/response/{id}', name: 'claim_response', methods: ['GET', 'POST'])]
    public function response(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResponseType::class, $claim);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettez à jour les attributs de la réclamation
            $claim->setStatus('responded');
            $claim->setClosuredate(new \DateTime());
    
            // Enregistrez les modifications dans la base de données
            $entityManager->flush();
    
            // Enregistrer la notification dans la base de données
            $notification = new Notification();
            $notification->setText('Your ' . $claim->getDate()->format('Y-m-d') . ' claim has been responded.');
            $notification->setIduser($claim->getIduser());
            
            $entityManager->persist($notification);
            $entityManager->flush();
    
            // Redirigez l'utilisateur vers une page de confirmation ou ailleurs
            return $this->redirectToRoute('app_claim_claims');
        }
    
        return $this->render('claim/response_form.html.twig', [
            'form' => $form->createView(),
            'claim' => $claim,
        ]);
    }
    




    #[Route('/claim/{id}/pdf', name: 'claim_pdf')]
    public function generatePdf(Claim $claim, Request $request): Response
    {
        // Logique pour générer le contenu du PDF à partir des données de la réclamation
        $pdfContent = $this->renderView('claim/pdf_template.html.twig', [
            'claim' => $claim,
        ]);

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        
        // Création de l'objet Dompdf
        $dompdf = new Dompdf($options);
        
        // Chargement du contenu HTML dans Dompdf
        $dompdf->loadHtml($pdfContent);

        // Rendu du PDF
        $dompdf->render();

        // Renvoi du PDF comme une réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="claim.pdf"',
        ]);
    }

    #[Route('/claim/{id}/satisfaction/{value}', name: 'claim_update_satisfaction')]
    public function updateSatisfaction(Claim $claim, int $value, EntityManagerInterface $entityManager): Response
    {
        // Mettez à jour la satisfaction de la réclamation avec la valeur sélectionnée
        $claim->setSatisfaction($this->getSatisfactionLevel($value));
    
        // Enregistrez les modifications dans la base de données
        $entityManager->flush();
    
        // Redirigez l'utilisateur vers la page de détail de la réclamation
        return $this->redirectToRoute('app_claim_show', ['id' => $claim->getId()]);
    }
    
    private function getSatisfactionLevel(int $value): string
    {
        // Logique pour obtenir le niveau de satisfaction correspondant au clic sur l'emoji
        // Par exemple, vous pouvez mapper les valeurs de 1 à 5 à des chaînes représentant les niveaux de satisfaction
        // Vous pouvez personnaliser cette logique en fonction de vos besoins
        switch ($value) {
            case 1:
                return 'Bad';
            case 2:
                return 'Not good';
            case 3:
                return 'Neutral';
            case 4:
                return 'Good';
            case 5:
                return 'Very good';
            default:
                return 'Not yet rated';
        }
    }
    private function validateRecaptcha($recaptchaResponse): bool
    {
        $secretKey = '6Lc2-MkpAAAAANTWUzqdsrEtSIQeeFArfuRP50nr'; // Clé secrète reCAPTCHA

        // Construire les données pour la requête de validation
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ];

        // Configurer les options de la requête HTTP
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        // Effectuer la requête vers l'API Google reCAPTCHA pour vérifier la réponse
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        // Analyser la réponse JSON de l'API reCAPTCHA
        $response = json_decode($result);

        // Retourner true si la réponse est valide (success = true)
        return $response->success;
    }


    



   
    
}