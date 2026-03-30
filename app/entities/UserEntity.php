<?php
namespace App\Entities;

use MVC\Core\BaseEntity;

class UserEntity extends BaseEntity
{
    private ?int $id = null;
    private string $username;
    private string $email;
    private string $password;
    private ?string $avatar = null;
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAvatar(): string
    {
        return $this->avatar ? $this->avatar : 'default_profile_picture.jpg';
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt !== null ? new \DateTimeImmutable($createdAt) : null;
    }

    public function getMemberSince(): string
    {
        if ($this->createdAt === null) {
            return 'Membre';
        }

        $now = new \DateTimeImmutable();
        $diff = $this->createdAt->diff($now);

        if ($diff->y >= 1) {
            return 'Membre depuis ' . $diff->y . ' ' . ($diff->y > 1 ? 'ans' : 'an');
        }
        if ($diff->m >= 1) {
            return 'Membre depuis ' . $diff->m . ' mois';
        }
        $days = $diff->days;
        return 'Membre depuis ' . $days . ' ' . ($days > 1 ? 'jours' : 'jour');
    }
}
