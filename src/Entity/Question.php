<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;
    
    #[ORM\Column(length: 255)]
    private ?string $text;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Formquestions::class)]
    private Collection $formquestions;

    public function __construct()
    {
        $this->formquestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, Formquestions>
     */
    public function getFormquestions(): Collection
    {
        return $this->formquestions;
    }

    public function addFormquestion(Formquestions $formquestion): static
    {
        if (!$this->formquestions->contains($formquestion)) {
            $this->formquestions->add($formquestion);
            $formquestion->setQuestion($this);
        }

        return $this;
    }

    public function removeFormquestion(Formquestions $formquestion): static
    {
        if ($this->formquestions->removeElement($formquestion)) {
            // set the owning side to null (unless already changed)
            if ($formquestion->getQuestion() === $this) {
                $formquestion->setQuestion(null);
            }
        }

        return $this;
    }

    

}
