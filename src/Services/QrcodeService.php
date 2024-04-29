<?php

namespace App\Services;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use TCPDF;

class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected BuilderInterface $builder;
    private string $namePng=''; // Property to store the generated file name
    private string $Png='';

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }
    public function qrcode($query)
    {
        $url = 'https://www.google.com/search?q=';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');

        $path = dirname(__DIR__, 2).'/public/';

        // Create the directory if it doesn't exist
        $eventImagesDir = $path . 'EventImages/';
        if (!file_exists($eventImagesDir)) {
            mkdir($eventImagesDir, 0777, true);
        }

        // Set qrcode
        $result = $this->builder
            ->data($url.$query)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->size(400)
            ->margin(10)
            ->labelText($dateString)
            ->labelAlignment(LabelAlignment::Center)
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->backgroundColor(new Color(221, 158, 3))
            ->build();

        // Generate name
        $this->namePng = uniqid('', '') . '.png';

        // Save img png
        $result->saveToFile($eventImagesDir . $this->namePng);
         $Png= $eventImagesDir . $this->namePng;
        return $result->getDataUri();
    }
    public function generatePdfFromQrCode(string $qrCodeImagePath): string
    {
        // Create new TCPDF instance
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Add a page
        $pdf->AddPage();

        // Set image scale factor
        $imgScale = 1;

        // Set image position and dimensions
        $pdf->Image($qrCodeImagePath, 10, 10, 100, 100, '', '', '', false, 300, '', false, false, 0, 'CM');

        // Output PDF to specified file path
        $pdfDirectory = dirname(__DIR__, 2) . '/public/pdf/';
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true);
        }
        $pdfFilePath = $pdfDirectory . uniqid('', '') . '.pdf';
        $pdf->Output($pdfFilePath, 'F');

        return $pdfFilePath;
    }

    public function getFileName(): string
    {
        return $this->Png ;
    }

}