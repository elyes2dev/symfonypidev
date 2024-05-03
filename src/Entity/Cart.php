<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(name:"priceTotal")]
    #[Assert\Positive]
    private ?float $pricetotal;

    
    #[ORM\ManyToOne(targetEntity: User::class,inversedBy:'carts')]
    #[ORM\JoinColumn(name:"idUser", referencedColumnName: "id")]
    private ?User $iduser;

    
    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: Composed::class)]
    private Collection $composed;

    #[ORM\OneToMany(mappedBy: 'idcart', targetEntity: Cart::class)]
    private Collection $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->idproduct = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->composed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPricetotal(): ?float
    {
        return $this->pricetotal;
    }

    public function setPricetotal(float $pricetotal): static
    {
        $this->pricetotal = $pricetotal;

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

    // /**
    //  * @return Collection<int, Product>
    //  */
    // public function getIdproduct(): Collection
    // {
    //     return $this->idproduct;
    // }

    // public function addIdproduct(Product $idproduct): static
    // {
    //     if (!$this->idproduct->contains($idproduct)) {
    //         $this->idproduct->add($idproduct);
    //     }

    //     return $this;
    // }

    // public function removeIdproduct(Product $idproduct): static
    // {
    //     $this->idproduct->removeElement($idproduct);

    //     return $this;
    // }

    /**
     * @return Collection<int, Cart>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setIdcart($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getIdcart() === $this) {
                $order->setIdcart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Composed>
     */
    public function getComposed(): Collection
    {
        return $this->composed;
    }

    public function addComposed(Composed $composed): static
    {
        if (!$this->composed->contains($composed)) {
            $this->composed->add($composed);
            $composed->setCart($this);
        }

        return $this;
    }

    public function removeComposed(Composed $composed): static
    {
        if ($this->composed->removeElement($composed)) {
            // set the owning side to null (unless already changed)
            if ($composed->getCart() === $this) {
                $composed->setCart(null);
            }
        }

        return $this;
    }

}
