<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
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
    private $subject;

    /**
     * @ORM\Column(type="integer")
     */
    private $fromUser;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="notifications")
     */
    private $toUser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="array")
     */
    private $view = [];

    public function __construct()
    {
        $this->toUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getFromUser(): ?int
    {
        return $this->fromUser;
    }

    public function setFromUser(int $fromUser): self
    {
        $this->fromUser = $fromUser;

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

    /**
     * @return Collection|User[]
     */
    public function getToUser(): Collection
    {
        return $this->toUser;
    }

    public function addToUser(User $toUser): self
    {
        if (!$this->toUser->contains($toUser)) {
            $this->toUser[] = $toUser;
        }

        return $this;
    }

    public function removeToUser(User $toUser): self
    {
        if ($this->toUser->contains($toUser)) {
            $this->toUser->removeElement($toUser);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getView(): ?array
    {
        return $this->view;
    }

    public function setView(array $view): self
    {
        $this->view = $view;

        return $this;
    }
}
