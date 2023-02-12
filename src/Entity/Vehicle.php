<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ApiResource(
    shortName: 'vehicle',
    operations: [
        new Get(),
        new Patch(
            denormalizationContext: [
                'groups' => ['vehicle:write', 'vehicle:item:write:patch']
            ]
        ),
        new GetCollection(),
        new Post(),
        new Delete()
    ],
    normalizationContext: [
        'groups' => ['vehicle:read']
    ],
    denormalizationContext: [
        'groups' => ['vehicle:write']
    ],
    paginationEnabled: true,
    paginationItemsPerPage: 8
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: ['register_number' => 'partial'])]
#[UniqueEntity(fields: ['register_number'])]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotNull]
    #[NotBlank]
    private ?string $register_number = null;

    #[ORM\OneToOne(mappedBy: 'vehicle_owner', cascade: ['persist', 'remove'])]
    private ?Insurance $insurance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['vehicle:read', 'insurance:read'])]
    public function getRegisterNumber(): ?string
    {
        return $this->register_number;
    }

    #[Groups(['vehicle:write'])]
    public function setRegisterNumber(string $register_number): self
    {
        $this->register_number = $register_number;

        return $this;
    }

    #[Groups(['vehicle:read'])]
    public function getInsurance(): ?Insurance
    {
        return $this->insurance;
    }

    public function setInsurance(Insurance $insurance): self
    {
        // set the owning side of the relation if necessary
        if ($insurance->getVehicleOwner() !== $this) {
            $insurance->setVehicleOwner($this);
        }

        $this->insurance = $insurance;

        return $this;
    }
}
