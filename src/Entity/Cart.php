<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product; // Import the Product entity class

#[ORM\Entity(repositoryClass:CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $size;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $quantity;
    

    #[ORM\Column(name:"priceTotal")]
    #[Assert\Positive]
    private ?float $priceTotal;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'carts')]
    #[ORM\JoinColumn(name:"idUser", referencedColumnName: "id")]
    private ?User $idUser;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'carts')]
    #[ORM\JoinColumn(name: "idProduct", referencedColumnName: "id")]
    private ?Product $idProduct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceTotal(): ?float
    {
        return $this->priceTotal;
    }

    public function setPriceTotal(float $priceTotal): self
    {
        $this->priceTotal = $priceTotal;
        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->idProduct;
    }

    public function setIdProduct(?Product $idProduct): self
    {
        $this->idProduct = $idProduct;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }


}
