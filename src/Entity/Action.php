<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $action = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?AboutUs $aboutUs = null;


    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getAboutUs(): ?AboutUs
    {
        return $this->aboutUs;
    }

    public function setAboutUs(?AboutUs $aboutUs): self
    {
        $this->aboutUs = $aboutUs;

        return $this;
    }
}
