<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlokedRepository")
 */
class Bloked
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
    private $idBloquer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idBloqued;

    public function __construct()
    {
        $this->message = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdBloquer(): ?int
    {
        return $this->id_bloquer;
    }

    public function setIdBloquer(int $id_bloquer): self
    {
        $this->id_bloquer = $id_bloquer;

        return $this;
    }

    public function getIdBloqued(): ?string
    {
        return $this->id_bloqued;
    }

    public function setIdBloqued(string $id_bloqued): self
    {
        $this->id_bloqued = $id_bloqued;

        return $this;
    }
}
