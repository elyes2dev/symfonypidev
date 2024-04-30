<?php

namespace App\Entity;

use App\Repository\FormquestionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:FormquestionsRepository::class)]
#[ORM\Table(name: "formquestions")]
class Formquestions
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'formquestions', cascade: ["remove"])]
    #[ORM\JoinColumn(name: "idQuestion", referencedColumnName: "id")]
    private ?Question $question;

    #[ORM\ManyToOne(targetEntity: Form::class, inversedBy: 'formquestions', cascade: ["remove"])]
    #[ORM\JoinColumn(name: "idForm", referencedColumnName: "id")]
    private ?Form $form;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): static
    {
        $this->form = $form;

        return $this;
    }


   

}
