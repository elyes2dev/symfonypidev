<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $type;

    
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    private ?User $iduser;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'idpayment')]
    private Collection $idevent;

    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'idpayment')]
    private Collection $idorder;

    #[ORM\ManyToMany(targetEntity: Reservation::class, mappedBy: 'idpayment')]
    private Collection $idreservation;

    #[ORM\ManyToMany(targetEntity: Subscription::class, mappedBy: 'idpayment')]
    private Collection $idsubscription;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idevent = new ArrayCollection();
        $this->idorder = new ArrayCollection();
        $this->idreservation = new ArrayCollection();
        $this->idsubscription = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getIdevent(): Collection
    {
        return $this->idevent;
    }

    public function addIdevent(Event $idevent): static
    {
        if (!$this->idevent->contains($idevent)) {
            $this->idevent->add($idevent);
            $idevent->addIdpayment($this);
        }

        return $this;
    }

    public function removeIdevent(Event $idevent): static
    {
        if ($this->idevent->removeElement($idevent)) {
            $idevent->removeIdpayment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getIdorder(): Collection
    {
        return $this->idorder;
    }

    public function addIdorder(Order $idorder): static
    {
        if (!$this->idorder->contains($idorder)) {
            $this->idorder->add($idorder);
            $idorder->addIdpayment($this);
        }

        return $this;
    }

    public function removeIdorder(Order $idorder): static
    {
        if ($this->idorder->removeElement($idorder)) {
            $idorder->removeIdpayment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getIdreservation(): Collection
    {
        return $this->idreservation;
    }

    public function addIdreservation(Reservation $idreservation): static
    {
        if (!$this->idreservation->contains($idreservation)) {
            $this->idreservation->add($idreservation);
            $idreservation->addIdpayment($this);
        }

        return $this;
    }

    public function removeIdreservation(Reservation $idreservation): static
    {
        if ($this->idreservation->removeElement($idreservation)) {
            $idreservation->removeIdpayment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getIdsubscription(): Collection
    {
        return $this->idsubscription;
    }

    public function addIdsubscription(Subscription $idsubscription): static
    {
        if (!$this->idsubscription->contains($idsubscription)) {
            $this->idsubscription->add($idsubscription);
            $idsubscription->addIdpayment($this);
        }

        return $this;
    }

    public function removeIdsubscription(Subscription $idsubscription): static
    {
        if ($this->idsubscription->removeElement($idsubscription)) {
            $idsubscription->removeIdpayment($this);
        }

        return $this;
    }

    public function setIdevent(string $idevent): static
    {
        $this->idevent = $idevent;

        return $this;
    }

    public function setIdorder(string $idorder): static
    {
        $this->idorder = $idorder;

        return $this;
    }

    public function setIdreservation(string $idreservation): static
    {
        $this->idreservation = $idreservation;

        return $this;
    }

    public function setIdsubscription(string $idsubscription): static
    {
        $this->idsubscription = $idsubscription;

        return $this;
    }

}
