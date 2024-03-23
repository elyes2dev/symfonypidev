<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $firstname;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lastname;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $phonenumber;
    
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $birthdate;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $location;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $gender;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $email;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $password;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $role;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $image;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?DateTimeInterface $creationdate;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $status;

    #[ORM\OneToMany(mappedBy: 'iduser', targetEntity: Club::class)]
    private Collection $clubs;

    #[ORM\OneToMany(mappedBy: 'iduser', targetEntity: Claim::class)]
    private Collection $claims;

    #[ORM\OneToMany(mappedBy: 'iduser', targetEntity: Cart::class)]
    private Collection $carts;

    #[ORM\OneToMany(mappedBy: 'userid', targetEntity: Useractivitylog::class)]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'idplayer', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'iduser', targetEntity: Payment::class)]
    private Collection $payments;

    #[ORM\ManyToMany(targetEntity: Stadium::class, mappedBy: 'iduser')]
    private Collection $refstadium;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'idplanner')]
    private Collection $idevent;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'idplayer')]
    private Collection $idParticipant ;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->refstadium = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idevent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idParticipant = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clubs = new ArrayCollection();
        $this->claims = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhonenumber(): ?int
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(int $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreationdate(): ?\DateTimeInterface
    {
        return $this->creationdate;
    }

    public function setCreationdate(\DateTimeInterface $creationdate): static
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Stadium>
     */
    public function getRefstadium(): Collection
    {
        return $this->refstadium;
    }

    public function addRefstadium(Stadium $refstadium): static
    {
        if (!$this->refstadium->contains($refstadium)) {
            $this->refstadium->add($refstadium);
            $refstadium->addIduser($this);
        }

        return $this;
    }

    public function removeRefstadium(Stadium $refstadium): static
    {
        if ($this->refstadium->removeElement($refstadium)) {
            $refstadium->removeIduser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getIdevent(): Collection
    {
        return $this->idevent;
    }

    public function addIdevent(Event $idevent): static
    {
        if (!$this->idevent->contains($idevent)) {
            $this->idevent->add($idevent);
            $idevent->addIdplanner($this);
        }

        return $this;
    }

    public function removeIdevent(Event $idevent): static
    {
        if ($this->idevent->removeElement($idevent)) {
            $idevent->removeIdplanner($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): static
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
            $club->setIduser($this);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getIduser() === $this) {
                $club->setIduser(null);
            }
        }

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
            $claim->setIduser($this);
        }

        return $this;
    }

    public function removeClaim(Claim $claim): static
    {
        if ($this->claims->removeElement($claim)) {
            // set the owning side to null (unless already changed)
            if ($claim->getIduser() === $this) {
                $claim->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setIduser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getIduser() === $this) {
                $cart->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Useractivitylog>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Useractivitylog $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setUserid($this);
        }

        return $this;
    }

    public function removeActivity(Useractivitylog $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getUserid() === $this) {
                $activity->setUserid(null);
            }
        }

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
            $reservation->setIdplayer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdplayer() === $this) {
                $reservation->setIdplayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setIduser($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getIduser() === $this) {
                $payment->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getIdParticipant(): Collection
    {
        return $this->idParticipant;
    }

    public function addIdParticipant(Event $idParticipant): static
    {
        if (!$this->idParticipant->contains($idParticipant)) {
            $this->idParticipant->add($idParticipant);
        }

        return $this;
    }

    public function removeIdParticipant(Event $idParticipant): static
    {
        $this->idParticipant->removeElement($idParticipant);

        return $this;
    }

}
