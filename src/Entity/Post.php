<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    private string $title;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $publishedAt;

    /**
     * @ORM\Column
     */
    private string $image;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min="10")
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private Collection $comments;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPublishedAt(): \DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
