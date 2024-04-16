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
    private ?string $name;

    #[ORM\Column(name:"dateDeb",type:"date")]
    private ?DateTimeInterface $datedeb;

    #[ORM\Column(name:"dateFin",type:"date")]
    private ?DateTimeInterface $datefin;

    #[ORM\Column(name:"startTime",type:"time")]
    private ?DateTimeInterface $starttime;

    #[ORM\Column(name:"endTime",type:"time")]
    private ?DateTimeInterface $endtime;

    #[ORM\Column(name:"nbrParticipants")]
    #[Assert\Positive]
    private ?int $nbrparticipants;

    #[ORM\Column]
    #[Assert\Positive]
    private ?float $price;

    #[ORM\ManyToOne(targetEntity: Club::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: "idClub", referencedColumnName: "id")]
    private ?Club $idclub;
    
    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'idevent')]
    #[ORM\JoinTable(name:"imageevent")]
    #[ORM\JoinColumn(name:"idEvent", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idImage", referencedColumnName:"id")]
    private Collection $idimage;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Likedevent::class)]
    private Collection $likedByUsers;


    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'idParticipant')]
    private Collection $idplayer;

    #[ORM\ManyToMany(targetEntity: Payment::class, inversedBy: 'idevent')]
    #[ORM\JoinTable(name:"paymentevent")]
    #[ORM\JoinColumn(name:"idEvent", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"idPayment", referencedColumnName:"id")]
    private Collection $idpayment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idimage = new ArrayCollection();
        $this->likedByUsers = new ArrayCollection();
        $this->idplayer = new ArrayCollection();
        $this->idpayment = new ArrayCollection();
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

    public function setIdimage(string $idimage): static
    {
        $this->idimage = $idimage;

        return $this;
    }

    public function addLikedByUser(LikedEvent $likedEvent): self
    {
    if (!$this->likedByUsers->contains($likedEvent)) {
        $this->likedByUsers[] = $likedEvent;
        $likedEvent->setEvent($this);
    }

    return $this;
    }

    public function removeLikedByUser(LikedEvent $likedEvent): self
    {
    if ($this->likedByUsers->removeElement($likedEvent)) {
        // set the owning side to null (unless already changed)
        if ($likedEvent->getEvent() === $this) {
            $likedEvent->setEvent(null);
        }
    }

    return $this;
    }

    /**
     * @return Collection<int, LikedEvent>
     */
    public function getLikedByUsers(): Collection
    {
        return $this->likedByUsers;
    }


    

}
