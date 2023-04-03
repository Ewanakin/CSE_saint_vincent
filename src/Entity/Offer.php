<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "discr", type: "string")]
#[DiscriminatorMap(["permanantoffer" => "PermanentOffer", "limitedoffer" => "LimitedOffer"])]
abstract class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $nbPlaces = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: OfferPicture::class, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, OfferPicture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(OfferPicture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setOffer($this);
        }

        return $this;
    }

    public function removePicture(OfferPicture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getOffer() === $this) {
                $picture->setOffer(null);
            }
        }

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\Callback('checkDate'));
        $metadata->addPropertyConstraint('price', new Assert\PositiveOrZero([
            'message' => 'Le prix en peut pas être négatif',
        ]));
        $metadata->addPropertyConstraint('nbPlaces', new Assert\Positive([
            'message' => 'le nombre de places ne peut pas être inférieur à 1',
        ]));
        $metadata->addConstraint(new Assert\Callback('checkPicturesCollectionSize'));

    }

    public function checkDate(ExecutionContextInterface $context)
    {
        $date = new \DateTime();
        if ($this->startDate > $this->endDate) {
            $context->buildViolation('La date de début ne peut pas être supérieur à la date de fin.')
                ->atPath('startDate')
                ->addViolation();
        }
        if ($this->endDate < $date) {
            $context->buildViolation('La date de fin ne peut pas être inférieur à la date actuelle.')
                ->atPath('endDate')
                ->addViolation();
        }
    }

    public function checkPicturesCollectionSize(ExecutionContextInterface $context)
    {
        if (count($this->pictures) > 4) {
            $context->buildViolation('Il ne peut pas y\'avoir plus de 4 images')
                ->atPath($this->pictures)
                ->addViolation();
        }
    }

    public function isTypeOffer(Offer $offer): string
    {
        $offerType = "";
        if (is_a($offer, LimitedOffer::class)){
            $offerType = "Offre limité";
        }
        if(is_a($offer, PermanentOffer::class)){
            $offerType = "Offre permanente";
        }
        return $offerType;
    }
}
