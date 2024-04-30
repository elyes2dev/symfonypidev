<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResponseRepository;
#[ORM\Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Feedback::class, inversedBy: 'responses')]
    #[ORM\JoinColumn(name: "feedback_id", referencedColumnName: "id")]
    private ?Feedback $feedback;

    #[ORM\ManyToOne(targetEntity: Formquestions::class)]
    #[ORM\JoinColumn(name: "question_id", referencedColumnName: "id")]
    private ?Formquestions $question;

    #[ORM\Column(type: "string")]
    private ?string $answer;

public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeedback(): ?Feedback
    {
        return $this->feedback;
    }

    public function setFeedback(?Feedback $feedback): static
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getQuestion(): ?Formquestions
    {
        return $this->question;
    }

    public function setQuestion(?Formquestions $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function __toString(): string
    {
        return $this->answer;
    }

}
