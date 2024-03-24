<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ClubRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\TimeType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SebastianBergmann\Timer\Timer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Timezone;

#[ORM\Entity(repositoryClass:ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $governorate;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $city;

    #[ORM\Column(name:"startTime")]
    #[Assert\NotBlank]
    private ?DateTime $starttime;

    #[ORM\Column(name:"endTime")]
    #[Assert\NotBlank]
    private ?DateTime $endtime;

    #[ORM\Column(name:"stadiumNbr")]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $stadiumnbr;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $description;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $longitude;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $latitude;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'clubs')]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    private ?User $iduser;

    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'idclub')]
    #[ORM\JoinTable(name:"imageclub")]
    #[ORM\JoinColumn(name:"idImage", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idClub", referencedColumnName:"id")]
    private Collection $idimage;

    #[ORM\OneToMany(mappedBy: 'idclub', targetEntity: Claim::class)]
    private Collection $claims;

    #[ORM\OneToMany(mappedBy: 'idclub', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'idclub', targetEntity: Event::class)]
    private Collection $stadiums;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idimage = new \Doctrine\Common\Collections\ArrayCollection();
        $this->claims = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->stadiums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGovernorate(): ?string
    {
        return $this->governorate;
    }

    public function setGovernorate(string $governorate): static
    {
        $this->governorate = $governorate;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

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

    public function getStadiumnbr(): ?int
    {
        return $this->stadiumnbr;
    }

    public function setStadiumnbr(int $stadiumnbr): static
    {
        $this->stadiumnbr = $stadiumnbr;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

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
     * @return Collection<int, Claim>
     */
    public function getClaims(): Collection
    {
        return $this->claims;
    }

    public function addClaim(Claim $claim): static
    {
        if (!$this->claims->contains($claim)) {
            $this->claims->add($claim);
            $claim->setIdclub($this);
        }

        return $this;
    }

    public function removeClaim(Claim $claim): static
    {
        if ($this->claims->removeElement($claim)) {
            // set the owning side to null (unless already changed)
            if ($claim->getIdclub() === $this) {
                $claim->setIdclub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setIdclub($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getIdclub() === $this) {
                $event->setIdclub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getStadiums(): Collection
    {
        return $this->stadiums;
    }

    public function addStadium(Event $stadium): static
    {
        if (!$this->stadiums->contains($stadium)) {
            $this->stadiums->add($stadium);
            $stadium->setIdclub($this);
        }

        return $this;
    }

    public function removeStadium(Event $stadium): static
    {
        if ($this->stadiums->removeElement($stadium)) {
            // set the owning side to null (unless already changed)
            if ($stadium->getIdclub() === $this) {
                $stadium->setIdclub(null);
            }
        }

        return $this;
    }

}
