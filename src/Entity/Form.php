<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ClaimRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;

#[ORM\Entity(repositoryClass:ClaimRepository::class)]
class Form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;
    
    #[ORM\Column(length: 255)]
    private ?string $name;
    
    #[ORM\Column(name:"creationDate",type:"date")]
    private ?DateTimeInterface $creationdate;

    #[ORM\OneToMany(mappedBy: 'form', targetEntity: Formquestions::class)]
    private Collection $formquestions;

    #[ORM\OneToMany(mappedBy: 'idForm', targetEntity: Feedback::class)]
    private Collection $feedbacks;

    public function __construct()
    {
        $this->formquestions = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreationdate(): ?\DateTimeInterface
    {
        return $this->creationdate;
    }

    public function setCreationdate(\DateTimeInterface $creationdate): static
    {
        $this->creationdate = $creationdate;

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
            $formquestion->setForm($this);
        }

        return $this;
    }

    public function removeFormquestion(Formquestions $formquestion): static
    {
        if ($this->formquestions->removeElement($formquestion)) {
            // set the owning side to null (unless already changed)
            if ($formquestion->getForm() === $this) {
                $formquestion->setForm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks->add($feedback);
            $feedback->setIdForm(null);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedbacks->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getIdForm() === $this) {
                $feedback->setIdForm(null);
            }
        }

        return $this;
    }


    

}
