<?php

namespace App\Service;

use App\Entity\LimitedOffer;
use App\Entity\Newsletter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendMailForLimitedOffer( Newsletter $newsletter, LimitedOffer $limitedOffer): void
    {

        $email = (new TemplatedEmail())
            ->from('newsletter@csesaint-vincent.fr')
            ->to($newsletter->getEmail())
            ->subject('Nouvelle offre limité !')
            ->htmlTemplate('emails/limitedOffer.html.twig')
            ->context([
                'limitedOffer' => $limitedOffer,
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $e = "an error occured";
        }
    }


}

