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
class Claim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type:"date")]
    private ?DateTimeInterface $date;
    
    #[ORM\Column(length: 255)]
    private ?string $description;
    
    #[ORM\Column(length: 255)]
    private ?string $type;

    #[ORM\Column(length: 255)]
    private ?string $status;

    #[ORM\Column(length: 255)]
    private ?string $satisfaction;

    #[ORM\Column(length: 255)]
    private ?string $image;

    #[ORM\Column(length: 255)]
    private ?string $response;

    #[ORM\Column(name:"closureDate",type:"date")]
    private ?DateTimeInterface $closuredate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'claims')]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    private ?User $iduser;

    #[ORM\ManyToOne(targetEntity: Club::class, inversedBy: 'claims')]
    #[ORM\JoinColumn(name: "idClub", referencedColumnName: "id")]
    private ?Club $idclub;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSatisfaction(): ?string
    {
        return $this->satisfaction;
    }

    public function setSatisfaction(string $satisfaction): static
    {
        $this->satisfaction = $satisfaction;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getClosuredate(): ?\DateTimeInterface
    {
        return $this->closuredate;
    }

    public function setClosuredate(\DateTimeInterface $closuredate): static
    {
        $this->closuredate = $closuredate;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdclub(): ?Club
    {
        return $this->idclub;
    }

    public function setIdclub(?Club $idclub): static
    {
        $this->idclub = $idclub;

        return $this;
    }


}
