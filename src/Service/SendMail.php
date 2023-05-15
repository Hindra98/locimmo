<?php

namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class SendMail
{
    public function __construct(private Environment $renderer)
    {
    }

    public function sendMailNotification($to, $subject, $contenu)
    {

        $transport = Transport::fromDsn('smtp://localhost:1025');
        $mailer = new Mailer($transport);
        $from = 'noreply@franckimmo.com';
        $email = (new Email())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->html($this->renderer->render('security/index.html.twig', [
                'to' => $to,
                'from' => $from,
                'subject' => $subject,
                'contenu' => $contenu
            ]));
        $mailer->send($email);

    }

}