<?php

namespace App\Controller;

use App\Entity\Event;
use App\Services\QrcodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TCPDF;

class QrCodeController extends AbstractController
{
    #[Route('/qrcode/{eventId}', name: 'generate_qrcode')]
    public function generateQRCode(Request $request, $eventId, QrcodeService $qrcodeService, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        // Fetch event data from the database
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Construct event data string to encode into QR code
        $eventData = sprintf(
            "Name: %s\nStart Date: %s\nEnd Date: %s\nPrice: %s\nStart Time: %s\nEnd Time: %s",
            $event->getName(),
            $event->getDatedeb()->format('Y-m-d'),
            $event->getDatefin()->format('Y-m-d'),
            $event->getPrice(),
            $event->getStarttime()->format('H:i:s'),
            $event->getEndtime()->format('H:i:s')
        );

        // Generate QR code
        $qrCodeDataUri = $qrcodeService->qrcode($eventData);

        // Create new TCPDF instance
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Add a page
        $pdf->AddPage();

        // Embed QR code image into PDF
        $pdf->Image('@' . $qrCodeDataUri, 10, 10, 100, 100, '', '', '', false, 300, '', false, false, 0, 'CM');

        // Output PDF to specified file path
        $pdfDirectory = dirname(__DIR__, 2) . '/public/pdf/';
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true);
        }
        $pdfFilePath = $pdfDirectory . uniqid('', '') . '.pdf';
        $pdf->Output($pdfFilePath, 'F');

        // Define email parameters
        $subject = 'QR Code for Event';
        $from = 'bchirben8@gmail.com';
        $to = 'bechirbenslimene8@gmail.com';
        $htmlContent = '<p>Here is your QR code for the event:</p>';
        $htmlContent .= '   http://127.0.0.1:8000/qrcode/'.$eventId;

        // Send email with PDF attachment
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($htmlContent);

        // Attach PDF to email
        $email->attachFromPath($pdfFilePath);

        // Send email
        $mailer->send($email);

        // Render the QR code page
        return $this->render('home/qrcode.html.twig', [
            'qrCode' => $qrCodeDataUri,
            'qrCodeFileName' => $pdfFilePath // Pass the PDF file path to the view
        ]);
    }
}
