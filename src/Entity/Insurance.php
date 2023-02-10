<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\InsuranceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

#[ORM\Entity(repositoryClass: InsuranceRepository::class)]
#[ApiResource(
    shortName: 'insurance',
    operations: [
        new Get(),
        new Patch(),
        new GetCollection(),
        new Post()
    ],
    normalizationContext: [
        'groups' => ['insurance:read']
    ],
    denormalizationContext: [
        'groups' => ['insurance:write']
    ],
    paginationEnabled: true,
    paginationItemsPerPage: 8
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(DateFilter::class, properties: ['oc', 'ac', 'nw', 'tacho'])]
class Insurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Expression('value === null or value >= this.getDateNow()')]
    private ?\DateTimeInterface $oc = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Expression('value === null or value >= this.getDateNow()')]
    private ?\DateTimeInterface $ac = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Expression('value === null or value >= this.getDateNow()')]
    private ?\DateTimeInterface $nw = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Expression('value === null or value >= this.getDateNow()')]
    private ?\DateTimeInterface $tacho = null;

    #[Groups(['insurance:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['insurance:read'])]
    public function getOc(): ?\DateTimeInterface
    {
        return $this->oc;
    }

    #[Groups(['insurance:write'])]
    public function setOc(?\DateTimeInterface $oc): self
    {
        $this->oc = $oc;

        return $this;
    }

    #[Groups(['insurance:read'])]
    public function getAc(): ?\DateTimeInterface
    {
        return $this->ac;
    }

    #[Groups(['insurance:write'])]
    public function setAc(?\DateTimeInterface $ac): self
    {
        $this->ac = $ac;

        return $this;
    }

    #[Groups(['insurance:read'])]
    public function getNw(): ?\DateTimeInterface
    {
        return $this->nw;
    }

    #[Groups(['insurance:write'])]
    public function setNw(?\DateTimeInterface $nw): self
    {
        $this->nw = $nw;

        return $this;
    }

    #[Groups(['insurance:read'])]
    public function getTacho(): ?\DateTimeInterface
    {
        return $this->tacho;
    }

    #[Groups(['insurance:write'])]
    public function setTacho(?\DateTimeInterface $tacho): self
    {
        $this->tacho = $tacho;

        return $this;
    }

    public function getDateNow(): \DateTimeInterface
    {
        return new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));
    }
}
