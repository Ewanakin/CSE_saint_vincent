<?php

namespace App\Events;

use App\Entity\LimitedOffer;
use App\Service\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class LimitedOfferEvent extends Event
{
    const NAME = 'limited.offer.event';

    protected LimitedOffer $offer;

    protected array $newsletters;

    private MailerInterface $mailerInterface;

    public function __construct(LimitedOffer $offer, array $newsletters, MailerInterface $mailerInterface)
    {
        $this->offer = $offer;
        $this->newsletters = $newsletters;
        $this->mailerInterface = $mailerInterface;
    }

    /**
     * @return LimitedOffer
     */
    public function getOffer(): LimitedOffer
    {
        return $this->offer;
    }

    /**
     * @return array
     */
    public function getNewsletters(): array
    {
        return $this->newsletters;
    }

    /**
     * @return Mailer
     */
    public function getMailerInterface(): MailerInterface
    {
        return $this->mailerInterface;
    }
}