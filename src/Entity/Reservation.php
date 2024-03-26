<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use DateTimeInterface;
use Doctrine\Migrations\Version\State;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type:"date")]
    private ?DateTimeInterface $date;

    #[ORM\Column(name:"startTime",type:"time")]
    private ?DateTimeInterface $starttime;

    #[ORM\Column(name:"endTime",type:"time")]
    private ?DateTimeInterface $endtime;

    #[ORM\Column(length: 255)]
    private ?string $type;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: "idPlayer", referencedColumnName: "id")]
    private ?User $idplayer;

    #[ORM\ManyToOne(targetEntity: Stadium::class,inversedBy:'reservations')]
    #[ORM\JoinColumn(name: 'refStadium', referencedColumnName: 'reference')]
    private ?Stadium $refstadium;

    #[ORM\ManyToMany(targetEntity: Payment::class, inversedBy: 'idreservation')]
    #[ORM\JoinTable(name:"paymentreservation")]
    #[ORM\JoinColumn(name:"idPayment", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idreservation", referencedColumnName:"id")]
    private Collection $idpayment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idpayment = new ArrayCollection();
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
