<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($claim, $email): void
    {
        // Composer le contenu de l'e-mail avec les détails de la recette
        $message = (new Email())
            ->from('saropez.pro@gmail.com')
            ->to($email)
            ->subject('Confirmation de reclamation ')
            ->text('Votre réclamation a été enregistrée avec succès.')
            ->html('<p>Description: ' . $claim->getDescription() . '</p>' . '<p>Nous traiterons votre réclamation dans les plus brefs délais. Merci.</p>');
            
                   
                   
                   
                   
        
        $this->mailer->send($message);
    }
}