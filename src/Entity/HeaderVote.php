<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeaderVoteRepository")
 */
class HeaderVote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $images;

  /*  
     @ORM\OneToOne(targetEntity="App\Entity\Vote", cascade={"persist", "remove"})
    private $vote;*/

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vote", inversedBy="header_vote")
     */
    private $vote_header;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setVote(?Vote $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getVoteHeader(): ?Vote
    {
        return $this->vote_header;
    }

    public function setVoteHeader(?Vote $vote_header): self
    {
        $this->vote_header = $vote_header;

        return $this;
    }
}
