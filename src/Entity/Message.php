<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_sender;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_receved;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $view;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bloked", inversedBy="message")
     */
    private $bloked;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->createdAt= new \DateTime();
        $this->view=0;
    }
    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIdSender(): ?int
    {
        return $this->id_sender;
    }

    public function setIdSender(int $id_sender): self
    {
        $this->id_sender = $id_sender;

        return $this;
    }

    public function getIdReceved(): ?int
    {
        return $this->id_receved;
    }

    public function setIdReceved(int $id_receved): self
    {
        $this->id_receved = $id_receved;

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

    public function getView(): ?bool
    {
        return $this->view;
    }

    public function setView(bool $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getBloked(): ?Bloked
    {
        return $this->bloked;
    }

    public function setBloked(?Bloked $bloked): self
    {
        $this->bloked = $bloked;

        return $this;
    }
}
