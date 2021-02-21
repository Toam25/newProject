<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserVoteRepository")
 */
class UserVote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\vote", inversedBy="userVotes")
     */
    private $vote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="userVotes")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVote(): ?vote
    {
        return $this->vote;
    }

    public function setVote(?vote $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
