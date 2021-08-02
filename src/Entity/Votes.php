<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VotesRepository")
 */
class Votes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="votes")
     */
    private $article;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="votesarticle")
     */
    private $votearticle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog", inversedBy="votes")
     */
    private $blog;

    /**
     * @ORM\ManyToOne(targetEntity=Boutique::class, inversedBy="votes")
     */
    private $boutique;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="votes")
     */
    private $comments;
    
    public function __construct()
    {
        $this->created_at= new \DateTime();
        $this->comments = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getVotearticle(): ?Article
    {
        return $this->votearticle;
    }

    public function setVotearticle(?Article $votearticle): self
    {
        $this->votearticle = $votearticle;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVotes($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getVotes() === $this) {
                $comment->setVotes(null);
            }
        }

        return $this;
    }
}
