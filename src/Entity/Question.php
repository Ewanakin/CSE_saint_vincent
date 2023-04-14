<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $question;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Reponse::class, cascade: ['remove'])]
    private Collection $reponses;

    #[ORM\Column(nullable: true, options: ["default"=> 0])]
    private ?int $activate = null;

    public function __toString()
    {
        return $this->question;
    }
    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponses(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponses): self
    {
        if ($this->reponses->removeElement($reponses)) {
            // set the owning side to null (unless already changed)
            if ($reponses->getQuestion() === $this) {
                $reponses->setQuestion(null);
            }
        }

        return $this;
    }

    public function getActivate(): ?int
    {
        return $this->activate;
    }

    public function setActivate(?int $activate): self
    {
        $this->activate = $activate;

        return $this;
    }
}
