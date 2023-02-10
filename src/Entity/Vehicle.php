<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ApiResource(
    shortName: 'vehicle'
)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[NotNull]
    #[Unique]
    private ?string $register_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegisterNumber(): ?string
    {
        return $this->register_number;
    }

    public function setRegisterNumber(string $register_number): self
    {
        $this->register_number = $register_number;

        return $this;
    }
}
