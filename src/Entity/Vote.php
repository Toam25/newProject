<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
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
    private $nbr_vote;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $images;

    /**
     * @ORM\Column(type="integer")
     */
    private $placement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Boutique", inversedBy="vote")
     */
    private $boutique;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="vote")
     */
    private $comments;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

       /** 
     * @Assert\NotBlank(message="Please, upload the photo.") 
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" }) 
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HeaderVote", mappedBy="vote_header")
     */
    private $header_vote;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->setNbrVote(0);
        $this->setCreateAt(new \DateTime());
        $this->header_vote = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrVote(): ?int
    {
        return $this->nbr_vote;
    }

    public function setNbrVote(int $nbr_vote): self
    {
        $this->nbr_vote = $nbr_vote;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getPlacement(): ?int
    {
        return $this->placement;
    }

    public function setPlacement(int $placement): self
    {
        $this->placement = $placement;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVote($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getVote() === $this) {
                $comment->setVote(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getPhotos()
    {
        return $this->photos;
    }

    public function setPhotos($photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @return Collection|HeaderVote[]
     */
    public function getHeaderVote(): Collection
    {
        return $this->header_vote;
    }

    public function addHeaderVote(HeaderVote $headerVote): self
    {
        if (!$this->header_vote->contains($headerVote)) {
            $this->header_vote[] = $headerVote;
            $headerVote->setVoteHeader($this);
        }

        return $this;
    }

    public function removeHeaderVote(HeaderVote $headerVote): self
    {
        if ($this->header_vote->contains($headerVote)) {
            $this->header_vote->removeElement($headerVote);
            // set the owning side to null (unless already changed)
            if ($headerVote->getVoteHeader() === $this) {
                $headerVote->setVoteHeader(null);
            }
        }

        return $this;
    }
}
