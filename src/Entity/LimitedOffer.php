<?php

namespace App\Entity;

use App\Repository\LimitedOfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LimitedOfferRepository::class)]
class LimitedOffer extends Offer
{

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $displayStartDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $displayEndDate = null;

    #[ORM\Column]
    private ?int $orderNumber = null;


    public function getDisplayStartDate(): ?\DateTimeInterface
    {
        return $this->displayStartDate;
    }

    public function setDisplayStartDate(\DateTimeInterface $displayStartDate): self
    {
        $this->displayStartDate = $displayStartDate;

        return $this;
    }

    public function getDisplayEndDate(): ?\DateTimeInterface
    {
        return $this->displayEndDate;
    }

    public function setDisplayEndDate(\DateTimeInterface $displayEndDate): self
    {
        $this->displayEndDate = $displayEndDate;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }
}
