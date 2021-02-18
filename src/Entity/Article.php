<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_promo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="article", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price_global;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $promo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wordKey;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Boutique", inversedBy="article")
     */
    private $boutique;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Votes", mappedBy="article")
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Votes", mappedBy="votearticle")
     */
    private $votesarticle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cart", mappedBy="articles")
     */
    private $carts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sous_category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $referency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $slide;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->votes = new ArrayCollection();
        $this->votesarticle = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->price=0;
        $this->price_global=0;
        $this->price_promo=0;
        $this->slide = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPricePromo(): ?int
    {
        return $this->price_promo;
    }

    public function setPricePromo(int $price_promo): self
    {
        $this->price_promo = $price_promo;

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

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    public function getPriceGlobal(): ?int
    {
        return $this->price_global;
    }

    public function setPriceGlobal(?int $price_global): self
    {
        $this->price_global = $price_global;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPromo(): ?string
    {
        return $this->promo;
    }

    public function setPromo(string $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getWordKey(): ?string
    {
        return $this->wordKey;
    }

    public function setWordKey(?string $wordKey): self
    {
        $this->wordKey = $wordKey;

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
     * @return Collection|Votes[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Votes $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setArticle($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getArticle() === $this) {
                $vote->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Votes[]
     */
    public function getVotesarticle(): Collection
    {
        return $this->votesarticle;
    }

    public function addVotesarticle(Votes $votesarticle): self
    {
        if (!$this->votesarticle->contains($votesarticle)) {
            $this->votesarticle[] = $votesarticle;
            $votesarticle->setVotearticle($this);
        }

        return $this;
    }

    public function removeVotesarticle(Votes $votesarticle): self
    {
        if ($this->votesarticle->contains($votesarticle)) {
            $this->votesarticle->removeElement($votesarticle);
            // set the owning side to null (unless already changed)
            if ($votesarticle->getVotearticle() === $this) {
                $votesarticle->setVotearticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->addArticle($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            $cart->removeArticle($this);
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSousCategory(): ?string
    {
        return $this->sous_category;
    }

    public function setSousCategory(?string $sous_category): self
    {
        $this->sous_category = $sous_category;

        return $this;
    }

    public function getReferency(): ?string
    {
        return $this->referency;
    }

    public function setReferency(string $referency): self
    {
        $this->referency = $referency;

        return $this;
    }

    public function getSlide(): ?int
    {
        return $this->slide;
    }

    public function setSlide(?int $slide): self
    {
        $this->slide = $slide;

        return $this;
    }
}
