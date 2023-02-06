<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank([], message: 'Le titre du souhait ne peut être blanc.')]
    #[Assert\Length([], min: 2, max: 255,
        minMessage: 'Le titre du souhait doit faire entre 2 et 250 caractères.',
        maxMessage: 'Le titre du souhait doit faire entre 2 et 250 caractères.')]
    #[ORM\Column(length: 250)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotBlank([], message: "L'auteur du souhait ne peut être blanc.")]
    #[Assert\Length([], min: 3, max: 50,
        minMessage: "L'auteur du souhait doit faire entre 3 et 50 caractères.",
        maxMessage: "L'auteur du souhait doit faire entre 3 et 50 caractères.")]
    #[ORM\Column(length: 50)]
    private ?string $author = null;

    #[Assert\Type(type: 'boolean')]
    #[ORM\Column]
    private ?bool $isPublished = null;

    #[Assert\GreaterThanOrEqual('today')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
