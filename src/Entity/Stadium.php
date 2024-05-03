<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\StadiumRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:StadiumRepository::class)]
class Stadium
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $reference;

    #[ORM\Column]
    #[Assert\Positive]
    private ?float $height;

    #[ORM\Column]
    #[Assert\Positive]
    private ?float $width;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $price;

    #[ORM\Column(type:"float")]
    #[Assert\Positive]
    private ?float $rate;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $maintenance;
    
    #[ORM\ManyToOne(targetEntity: Club::class, inversedBy: 'stadiums')]
    #[ORM\JoinColumn(name: "idClub", referencedColumnName: "id")]
    private ?Club $idclub;

    #[ORM\OneToMany(mappedBy: 'refstadium', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'refstadium')]
    #[ORM\JoinTable(name:"imagestadium")]
    #[ORM\JoinColumn(name:"refStadium", referencedColumnName:"reference")]
    #[ORM\InverseJoinColumn(name:"idImage", referencedColumnName:"id")]
    private Collection $idimage;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'refstadium')]
    private Collection $iduser;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idimage = new ArrayCollection();
        $this->iduser = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getMaintenance(): ?int
    {
        return $this->maintenance;
    }

    public function setMaintenance(?int $maintenance): static
    {
        $this->maintenance = $maintenance;

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
     * @return Collection<int, User>
     */
    public function getIduser(): Collection
    {
        return $this->iduser;
    }

    public function addIduser(User $iduser): static
    {
        if (!$this->iduser->contains($iduser)) {
            $this->iduser->add($iduser);
        }

        return $this;
    }

    public function removeIduser(User $iduser): static
    {
        $this->iduser->removeElement($iduser);

        return $this;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRefStadium($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRefStadium() === $this) {
                $reservation->setRefStadium(null);
            }
        }

        return $this;
    }

    public function setIdimage(string $idimage): static
    {
        $this->idimage = $idimage;

        return $this;
    }

}
