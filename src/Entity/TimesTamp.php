<?php

use Doctrine\ORM\Mapping as ORM;

trait TimesTamp
{
    /**
     * @ORM\Column(type="datetime")
     */

    private $createdAt;

    /**
     * @return mixed
     */

    public function getCreatedAt()
    {
        return $this->createdAt();
    }
    /**
     * Undocumented function
     *
     * @ORM\PrePersist()
     * 
     */
    public function prePersiste()
    {
        $this->createdAt = new \DateTime();
    }
}
