<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $reference;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $price;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $type;

    #[ORM\Column(name:"quantityStock")]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $quantitystock;


    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Composed::class)]
    private Collection $composed;

    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'idproduct')]
    #[ORM\JoinTable(name:"imageproduct")]
    #[ORM\JoinColumn(name:"idProduct", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idImage", referencedColumnName:"id")]
    private Collection $idimage;

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->idcart = new ArrayCollection();
        $this->idimage = new ArrayCollection();
        $this->composed = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function getQuantitystock(): ?int
    {
        return $this->quantitystock;
    }

    public function setQuantitystock(int $quantitystock): static
    {
        $this->quantitystock = $quantitystock;

        return $this;
    }

    // /**
    //  * @return Collection<int, Cart>
    //  */
    // public function getIdcart(): Collection
    // {
    //     return $this->idcart;
    // }

    // public function addIdcart(Cart $idcart): static
    // {
    //     if (!$this->idcart->contains($idcart)) {
    //         $this->idcart->add($idcart);
    //         $idcart->addIdproduct($this);
    //     }

    //     return $this;
    // }

    // public function removeIdcart(Cart $idcart): static
    // {
    //     if ($this->idcart->removeElement($idcart)) {
    //         $idcart->removeIdproduct($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Image>
     */
    public function getIdimage(): Collection
    {
        return $this->idimage;
    }

    public function addIdimage(Image $idimage): static
    {
        if (!$this->idimage->contains($idimage)) {
            $this->idimage->add($idimage);
        }

        return $this;
    }

    public function removeIdimage(Image $idimage): static
    {
        $this->idimage->removeElement($idimage);

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
            $composed->setProduct($this);
        }

        return $this;
    }

    public function removeComposed(Composed $composed): static
    {
        if ($this->composed->removeElement($composed)) {
            // set the owning side to null (unless already changed)
            if ($composed->getProduct() === $this) {
                $composed->setProduct(null);
            }
        }

        return $this;
    }

}
