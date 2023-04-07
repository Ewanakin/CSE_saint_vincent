<?php

namespace App\Listeners;
use App\Events\LimitedOfferEvent;
use App\Service\Mailer;

class LimitedOfferListener
{
    public function onLimitedOfferEvent(LimitedOfferEvent $event): void
    {
        $mailer = new Mailer($event->getMailerInterface());
        $newsletterMails = $event->getNewsletters();

        foreach ($newsletterMails as $newsletterMail){
            $mailer->sendMailForLimitedOffer($newsletterMail, $event->getOffer());
        }
    }
}