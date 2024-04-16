<?php

namespace App\Entity;

use App\Repository\TaskEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TaskEventRepository::class)]
class TaskEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 1)]
     private ?string $Description;

    #[ORM\Column(length: 255)]
    private ?string $Etat ;

    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $CreationDate ;

    #[ORM\Column(type:"datetime")]
    private ?DateTimeInterface $UpdatedDate ;

    #[ORM\ManyToOne(inversedBy: 'taskEvents')]
    private ?Event $EventId ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        // Validate if the value is one of the allowed enum values
        $allowedEtatValues = ['Pending', 'In Progress', 'Done'];
        if (!in_array($Etat, $allowedEtatValues)) {
            throw new \InvalidArgumentException("Invalid etat value: $Etat");
        }

        $this->Etat = $Etat;
        return $this;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(?DateTimeInterface $CreationDate): static
    {
        $this->CreationDate = $CreationDate;
    
        return $this;
    
     
    }
    
    

    public function getUpdatedDate(): ?DateTimeInterface
    {
        return $this->UpdatedDate;
    }
    public function setUpdatedDate(?DateTimeInterface $UpdatedDate): static
{
    $this->UpdatedDate = $UpdatedDate;
    
    return $this;
}
    

public function getEventId(): ?Event
{
    return $this->EventId;
}

    public function setEventId(?Event $EventId): static
    {
        $this->EventId = $EventId;

        return $this;
    }
}