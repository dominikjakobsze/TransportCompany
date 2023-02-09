<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InsuranceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InsuranceRepository::class)]
#[ApiResource]
class Insurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $oc = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ac = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $nw = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tacho = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOc(): ?\DateTimeInterface
    {
        return $this->oc;
    }

    public function setOc(?\DateTimeInterface $oc): self
    {
        $this->oc = $oc;

        return $this;
    }

    public function getAc(): ?\DateTimeInterface
    {
        return $this->ac;
    }

    public function setAc(?\DateTimeInterface $ac): self
    {
        $this->ac = $ac;

        return $this;
    }

    public function getNw(): ?\DateTimeInterface
    {
        return $this->nw;
    }

    public function setNw(?\DateTimeInterface $nw): self
    {
        $this->nw = $nw;

        return $this;
    }

    public function getTacho(): ?\DateTimeInterface
    {
        return $this->tacho;
    }

    public function setTacho(?\DateTimeInterface $tacho): self
    {
        $this->tacho = $tacho;

        return $this;
    }
}
