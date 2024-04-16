<?php

namespace App\Entity;

use App\Repository\LikedEventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:LikedEventRepository::class)]
#[ORM\Table(name: "likedevent")]
class Likedevent
{

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'likedevent')]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    private ?User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'likedevent')]
    #[ORM\JoinColumn(name: "idEvent", referencedColumnName: "id")]
    private ?Event $event;

    #[ORM\Column]
    private ?int $rating;


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }
}
