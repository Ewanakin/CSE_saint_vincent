<?php

namespace App\Entity;

use App\Repository\LimitedOfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\Callback('checkDate'));
        $metadata->addPropertyConstraint('orderNumber', new Assert\PositiveOrZero());
    }

    public function checkDate(ExecutionContextInterface $context)
    {
        $date = new \DateTime();
        if ($this->displayStartDate > $this->displayEndDate) {
            $context->buildViolation('La date de début d\'affichage ne peut pas être supérieur à la date de fin.')
                ->atPath('startDate')
                ->addViolation();
        }
        if ($this->displayEndDate < $date) {
            $context->buildViolation('La date de fin d\'affichage ne peut pas être inférieur à la date actuelle.')
                ->atPath('endDate')
                ->addViolation();
        }
    }
}
