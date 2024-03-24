<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:OrderRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $reference;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTime $date;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: "idCart", referencedColumnName: "id")]
    private ?User $idcart;

    #[ORM\ManyToMany(targetEntity: Payment::class, inversedBy: 'idorder')]
    #[ORM\JoinTable(name:"paymentorder")]
    #[ORM\JoinColumn(name:"idPayment", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idOrder", referencedColumnName:"id")]
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
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

    public function getIdcart(): ?Cart
    {
        return $this->idcart;
    }

    public function setIdcart(?Cart $idcart): static
    {
        $this->idcart = $idcart;

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
