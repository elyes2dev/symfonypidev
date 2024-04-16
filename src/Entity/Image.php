<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\Column(length: 255)]
    private ?string $url;

    #[ORM\Column(length: 255)]
    private ?string $type;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'idimage')]
    private Collection $idproduct;

    #[ORM\ManyToMany(targetEntity: Club::class, mappedBy: 'idimage')]
    private Collection $idclub;
    
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'idimage')]
    private Collection $idevent;

    #[ORM\ManyToMany(targetEntity: Stadium::class, mappedBy: 'idimage')]
    private Collection $refstadium;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idproduct = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idclub = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idevent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->refstadium = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

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

    /**
     * @return Collection<int, Product>
     */
    public function getIdproduct(): Collection
    {
        return $this->idproduct;
    }

    public function addIdproduct(Product $idproduct): static
    {
        if (!$this->idproduct->contains($idproduct)) {
            $this->idproduct->add($idproduct);
            $idproduct->addIdimage($this);
        }

        return $this;
    }

    public function removeIdproduct(Product $idproduct): static
    {
        if ($this->idproduct->removeElement($idproduct)) {
            $idproduct->removeIdimage($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getIdclub(): Collection
    {
        return $this->idclub;
    }

    public function addIdclub(Club $idclub): static
    {
        if (!$this->idclub->contains($idclub)) {
            $this->idclub->add($idclub);
            $idclub->addIdimage($this);
        }

        return $this;
    }

    public function removeIdclub(Club $idclub): static
    {
        if ($this->idclub->removeElement($idclub)) {
            $idclub->removeIdimage($this);
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
            $idevent->addIdimage($this);
        }

        return $this;
    }

    public function removeIdevent(Event $idevent): static
    {
        if ($this->idevent->removeElement($idevent)) {
            $idevent->removeIdimage($this);
        }

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
            $refstadium->addIdimage($this);
        }

        return $this;
    }

    public function removeRefstadium(Stadium $refstadium): static
    {
        if ($this->refstadium->removeElement($refstadium)) {
            $refstadium->removeIdimage($this);
        }

        return $this;
    }

    public function setIdproduct(string $idproduct): static
    {
        $this->idproduct = $idproduct;

        return $this;
    }

    public function setIdclub(string $idclub): static
    {
        $this->idclub = $idclub;

        return $this;
    }

    public function setIdevent(string $idevent): static
    {
        $this->idevent = $idevent;

        return $this;
    }

    public function setRefstadium(string $refstadium): static
    {
        $this->refstadium = $refstadium;

        return $this;
    }

}
