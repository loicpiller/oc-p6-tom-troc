<?php
namespace App\Entities;

use MVC\Core\BaseEntity;
use App\Entities\UserEntity;
use App\Entities\BookStatusEntity;

class BookEntity extends BaseEntity
{
    private ?int $id = null;
    private string $title;
    private ?string $author;
    private ?string $image = null;
    private ?string $description;

    private int $user_id;
    private ?UserEntity $user = null;

    private int $status_id;
    private ?BookStatusEntity $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getUser(): ?UserEntity 
    {
        return $this->user;
    }

    public function setUser(UserEntity $user): void
    {
        $this->user = $user;
        $this->user_id = $user->getId();
    }

    public function getStatusId(): int 
    {
        return $this->status_id;
    }

    public function setStatusId(int $statusId): void
    {
        $this->status_id = $statusId;
    }

    public function getStatus(): ?BookStatusEntity 
    {
        return $this->status;
    }

    public function setStatus(BookStatusEntity $status): void
    {
        $this->status = $status;
        $this->status_id = $status->getId();
    }
}

