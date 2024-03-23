<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $date;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $starttime;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $endtime;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $type;


    #[ORM\ManyToOne(targetEntity: User::class,inversedBy:'reservations')]
    private ?User $idplayer =null;

    #[ORM\ManyToOne(inversedBy:'reservations')]
    private ?Stadium $refstadium =null;

    #[ORM\ManyToMany(targetEntity: Payment::class, inversedBy: 'idreservation')]
    private Collection $idpayment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idpayment = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTimeInterface $starttime): static
    {
        $this->starttime = $starttime;

        return $this;
    }

    public function getEndtime(): ?\DateTimeInterface
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTimeInterface $endtime): static
    {
        $this->endtime = $endtime;

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

    public function getIdplayer(): ?User
    {
        return $this->idplayer;
    }

    public function setIdplayer(?User $idplayer): static
    {
        $this->idplayer = $idplayer;

        return $this;
    }

    public function getRefstadium(): ?Stadium
    {
        return $this->refstadium;
    }

    public function setRefstadium(?Stadium $refstadium): static
    {
        $this->refstadium = $refstadium;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getIdpayment(): Collection
    {
        return $this->idpayment;
    }

    public function addIdpayment(Payment $idpayment): static
    {
        if (!$this->idpayment->contains($idpayment)) {
            $this->idpayment->add($idpayment);
        }

        return $this;
    }

    public function removeIdpayment(Payment $idpayment): static
    {
        $this->idpayment->removeElement($idpayment);

        return $this;
    }

}
