<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoutiqueRepository")
 */
class Boutique
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="boutiques")
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $apropos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="boutique")
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Header", mappedBy="boutique")
     */
    private $headers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SocialNetwork", mappedBy="boutique")
     */
    private $socialNetworks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Slider", mappedBy="boutique")
     */
    private $sliders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reference", mappedBy="boutique")
     */
    private $shopReferences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Menu", mappedBy="boutique")
     */
    private $menus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EsArticle", mappedBy="boutique")
     */
    private $esArticles;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $user_condition;

    public function __construct()
    {
        $this->logo = "images_default/default_logo.png";
        $this->article = new ArrayCollection();
        $this->headers = new ArrayCollection();
        $this->socialNetworks = new ArrayCollection();
        $this->sliders = new ArrayCollection();
        $this->shopReferences = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->esArticles = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

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

    public function getApropos(): ?string
    {
        return $this->apropos;
    }

    public function setApropos(?string $apropos): self
    {
        $this->apropos = $apropos;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setBoutique($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getBoutique() === $this) {
                $article->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Header[]
     */
    public function getHeaders(): Collection
    {
        return $this->headers;
    }

    public function addHeader(Header $header): self
    {
        if (!$this->headers->contains($header)) {
            $this->headers[] = $header;
            $header->setBoutique($this);
        }

        return $this;
    }

    public function removeHeader(Header $header): self
    {
        if ($this->headers->contains($header)) {
            $this->headers->removeElement($header);
            // set the owning side to null (unless already changed)
            if ($header->getBoutique() === $this) {
                $header->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SocialNetwork[]
     */
    public function getSocialNetworks(): Collection
    {
        return $this->socialNetworks;
    }

    public function addSocialNetworks(SocialNetwork $socialNetworks): self
    {
        if (!$this->socialNetworks->contains($socialNetworks)) {
            $this->socialNetworks[] = $socialNetworks;
            $socialNetworks->setBoutique($this);
        }

        return $this;
    }

    public function removeSocialNetworks(SocialNetwork $socialNetworks): self
    {
        if ($this->socialNetworks->contains($socialNetworks)) {
            $this->socialNetworks->removeElement($socialNetworks);
            // set the owning side to null (unless already changed)
            if ($socialNetworks->getBoutique() === $this) {
                $socialNetworks->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Slider[]
     */
    public function getSliders(): Collection
    {
        return $this->sliders;
    }

    public function addSlider(Slider $slider): self
    {
        if (!$this->sliders->contains($slider)) {
            $this->sliders[] = $slider;
            $slider->setBoutique($this);
        }

        return $this;
    }

    public function removeSlider(Slider $slider): self
    {
        if ($this->sliders->contains($slider)) {
            $this->sliders->removeElement($slider);
            // set the owning side to null (unless already changed)
            if ($slider->getBoutique() === $this) {
                $slider->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reference[]
     */
    public function getShopReferences(): Collection
    {
        return $this->shopReferences;
    }

    public function addShopReference(Reference $shopReference): self
    {
        if (!$this->shopReferences->contains($shopReference)) {
            $this->shopReferences[] = $shopReference;
            $shopReference->setBoutique($this);
        }

        return $this;
    }

    public function removeShopReference(Reference $shopReference): self
    {
        if ($this->shopReferences->contains($shopReference)) {
            $this->shopReferences->removeElement($shopReference);
            // set the owning side to null (unless already changed)
            if ($shopReference->getBoutique() === $this) {
                $shopReference->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setBoutique($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->contains($menu)) {
            $this->menus->removeElement($menu);
            // set the owning side to null (unless already changed)
            if ($menu->getBoutique() === $this) {
                $menu->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EsArticle[]
     */
    public function getEsArticles(): Collection
    {
        return $this->esArticles;
    }

    public function addEsArticle(EsArticle $esArticle): self
    {
        if (!$this->esArticles->contains($esArticle)) {
            $this->esArticles[] = $esArticle;
            $esArticle->setBoutique($this);
        }

        return $this;
    }

    public function removeEsArticle(EsArticle $esArticle): self
    {
        if ($this->esArticles->contains($esArticle)) {
            $this->esArticles->removeElement($esArticle);
            // set the owning side to null (unless already changed)
            if ($esArticle->getBoutique() === $this) {
                $esArticle->setBoutique(null);
            }
        }

        return $this;
    }

    public function getUserCondition(): ?string
    {
        return $this->user_condition;
    }

    public function setUserCondition(?string $user_condition): self
    {
        $this->user_condition = $user_condition;

        return $this;
    }
}
