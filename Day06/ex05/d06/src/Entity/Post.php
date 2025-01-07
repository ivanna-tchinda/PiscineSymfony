<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
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
    #[JoinTable('author')]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(
        name: 'post_likes', // Nom explicite pour éviter le conflit avec la table "post_user"
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
        name: 'post_unlikes', // Nom explicite pour cette table intermédiaire
        joinColumns: [
            new ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $unlikes;

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

    public function removeLike(User $like){
        $this->likes->remove($like);
    }

    // public function getUnlikes(): Collection
    // {
    //     return $this->unlikes;
    // }

    // public function addUnlike(User $unlike): self
    // {
    //     if(!$this->unlikes->contains($unlike)){
    //         $this->unlikes[] = $unlike;
    //     }

    //     return $this;
    // }

    // public function removeUnlike(User $unlike){
    //     $this->unlikes->remove($unlike);
    // }
}
