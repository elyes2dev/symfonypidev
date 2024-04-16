<?php

namespace App\Entity;

use App\Repository\ComposedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:ComposedRepository::class)]
#[ORM\Table(name: "composed")]
class Composed
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'composed')]
    #[ORM\JoinColumn(name: "idCart", referencedColumnName: "id")]
    private ?Cart $cart;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'composed')]
    #[ORM\JoinColumn(name: "idProduct", referencedColumnName: "id")]
    private ?Product $product;

    #[ORM\Column]
    private ?int $quantity;

    #[ORM\Column]
    private ?string $size;

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): void
    {
        $this->size = $size;
    }
}
