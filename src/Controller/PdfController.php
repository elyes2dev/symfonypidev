<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Product;
use App\Form\ProductType;
use App\Entity\Image;
use App\Repository\ProductRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;


class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(): Response
    {
        return $this->render('cart/print.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }

    #[Route('/listS', name: 'generate_cart_pdf')]
    public function pdf(CartRepository $CartRepository): Response
    {
       // Configure Dompdf according to your needs
       $pdfOptions = new Options();
       $pdfOptions->set('defaultFont', 'Open Sans');

       // Instantiate Dompdf with our options
       $dompdf = new Dompdf($pdfOptions);
       // Retrieve the HTML generated in our twig file
       $html = $this->renderView('cart/print.html.twig', [
           'carts' => $CartRepository->findAll(),
       ]);

       // Add header HTML to $html variable
       $headerHtml = '<h1 style="text-align: center; color: #b00707;">Products List</h1>';
       $html = $headerHtml . $html;

       // Load HTML to Dompdf
       $dompdf->loadHtml($html);
       // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
       $dompdf->setPaper('A3', 'portrait');

       // Render the HTML as PDF
       $dompdf->render();
       
       // Output the generated PDF to Browser (inline view)
       return new Response($dompdf->output(), Response::HTTP_OK, [
           'Content-Type' => 'application/pdf',
       ]);
    }
}
