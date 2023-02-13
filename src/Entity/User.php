<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    shortName: 'user',
    operations: [
        new Post(),
        new Delete(),
        new GetCollection()
        //delete new Delete Later
    ],
    normalizationContext: [
        'groups' => ['user:read']
    ],
    denormalizationContext: [
        'groups' => ['user:write']
    ],
)]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[NotBlank]
    #[NotNull]
    #[Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[NotNull]
    private ?string $password = null;

    #[Groups(['user:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['user:read'])]
    public function getEmail(): ?string
    {
        return $this->email;
    }

    #[Groups(['user:write'])]
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    #[Groups(['user:read'])]
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    #[Groups(['user:write'])]
    public function setPassword(string $password): self
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $this->password = $password;

        return $this;
    }
}
