<?php

namespace App\Entity;

use App\Repository\UserActivityLogRepository;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:UserActivityLogRepository::class)]
class Useractivitylog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $logid;

    #[ORM\Column(name:"loginTime",type:"time")]
    private ?DateTime $logintimestamp;

    #[ORM\Column(name:"loginSuccess")]
    private ?bool $loginsuccess;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    private ?User $userid;

    public function getLogid(): ?int
    {
        return $this->logid;
    }

    public function getLogintimestamp(): ?\DateTimeInterface
    {
        return $this->logintimestamp;
    }

    public function setLogintimestamp(\DateTimeInterface $logintimestamp): static
    {
        $this->logintimestamp = $logintimestamp;

        return $this;
    }

    public function isLoginsuccess(): ?bool
    {
        return $this->loginsuccess;
    }

    public function setLoginsuccess(?bool $loginsuccess): static
    {
        $this->loginsuccess = $loginsuccess;

        return $this;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    public function getLoginsuccess(): ?string
    {
        return $this->loginsuccess;
    }


}
