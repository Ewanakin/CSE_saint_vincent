<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $website;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(length: 255)]
    private string $picture;

    #[ORM\OneToMany(mappedBy: 'partner', targetEntity: PartnerPicture::class)]
    private Collection $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, PartnerPicture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(PartnerPicture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setPartner($this);
        }

        return $this;
    }

    public function removePicture(PartnerPicture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPartner() === $this) {
                $picture->setPartner(null);
            }
        }

        return $this;
    }
}
