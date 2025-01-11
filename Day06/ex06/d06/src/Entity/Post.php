<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $created = null;

    #[ORM\ManyToOne(inversedBy: 'post', cascade: ['persist', 'remove'])]
    #[JoinTable('last_update_user')]
    private ?User $last_update_user = null;

    #[ORM\Column(length: 255)]
    private ?string $last_update_datetime = null;

    #[ORM\ManyToOne(inversedBy: 'post', cascade: ['persist', 'remove'])]
    #[JoinTable('author')]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(
        name: 'post_likes',
        joinColumns: [
            new ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $likes;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(
        name: 'post_dislikes',
        joinColumns: [
            new ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $dislikes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(string $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getLastUpdateUser(): ?User
    {
        return $this->last_update_user;
    }

    public function setLastUpdateUser(User $last_update_user): static
    {
        $this->last_update_user = $last_update_user;

        return $this;
    }

    public function getLastUpdateTime(): ?string
    {
        return $this->last_update_datetime;
    }

    public function setLastUpdateTime(string $last_update_datetime): static
    {
        $this->last_update_datetime = $last_update_datetime;

        return $this;
    }

    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(User $like): self
    {
        if(!$this->likes->contains($like)){
            $this->likes[] = $like;
        }

        return $this;
    }

    public function isLikedByUser(User $user){
        return $this->likes->contains($user);
    }

    public function removeLike(User $like){
        return $this->likes->removeElement($like);
    }

    public function getDislikes(): Collection
    {
        return $this->dislikes;
    }

    public function addDislike(User $dislike): self
    {
        if(!$this->dislikes->contains($dislike)){
            $this->dislikes[] = $dislike;
        }

        return $this;
    }
    public function isDislikedByUser(User $user){
        return $this->dislikes->contains($user);
    }

    public function removeDislike(User $dislike){
        $this->dislikes->removeElement($dislike);
    }
}
