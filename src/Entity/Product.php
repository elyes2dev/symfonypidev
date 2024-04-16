<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9]+$/",
        message: "Reference must contain only letters and numbers"
    )]
    private ?string $reference;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, minMessage: "Name must be at least 4 characters long")]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $price;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ["T-shirt", "Sneakers"], message: "Please choose a valid type.")]
    private ?string $type;

    #[ORM\Column(name:"quantityStock")]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(value: 40, message: "The quantity must be less than or equal to 40.")]
    private ?int $quantitystock;
    

    #[ORM\Column(length: 255)]
    
    private ?string $image;


    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Composed::class)]
    private Collection $composed;

    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'idproduct')]
    #[ORM\JoinTable(name:"imageproduct")]
    //#[ORM\JoinColumn(name:"idImage", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idProduct", referencedColumnName:"id")]
    private Collection $idimage;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Existing constructor logic...
        $this->image = '';
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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
