<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\EventRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass:EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $datedeb;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $datefin;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $starttime;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $endtime;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $nbrparticipants;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $price;

    #[ORM\ManyToOne(targetEntity: Club::class,inversedBy:'events')]
    private ?Club $idclub =null;

    
    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'idevent')]
    private Collection $idimage;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'idevent')]
    private Collection $idplanner ;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'idParticipant')]
    private Collection $idplayer;

    #[ORM\ManyToMany(targetEntity: Payment::class, inversedBy: 'idevent')]
    private Collection $idpayment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idimage = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idplanner = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idplayer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idpayment = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDatedeb(\DateTimeInterface $datedeb): static
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

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

    public function getNbrparticipants(): ?int
    {
        return $this->nbrparticipants;
    }

    public function setNbrparticipants(int $nbrparticipants): static
    {
        $this->nbrparticipants = $nbrparticipants;

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
    public function getIdplanner(): Collection
    {
        return $this->idplanner;
    }

    public function addIdplanner(User $idplanner): static
    {
        if (!$this->idplanner->contains($idplanner)) {
            $this->idplanner->add($idplanner);
        }

        return $this;
    }

    public function removeIdplanner(User $idplanner): static
    {
        $this->idplanner->removeElement($idplanner);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdplayer(): Collection
    {
        return $this->idplayer;
    }

    public function addIdplayer(User $idplayer): static
    {
        if (!$this->idplayer->contains($idplayer)) {
            $this->idplayer->add($idplayer);
        }

        return $this;
    }

    public function removeIdplayer(User $idplayer): static
    {
        $this->idplayer->removeElement($idplayer);

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
