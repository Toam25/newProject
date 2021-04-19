<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Boutique", mappedBy="user")
     */
    private $boutiques;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Votes", mappedBy="user")
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cart", mappedBy="user")
     */
    private $carts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="user")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserVote", mappedBy="user")
     */
    private $userVotes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="user")
     */
    private $blogs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastActivityAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Notification", mappedBy="toUser")
     */
    private $notifications;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confimation;

    /**
     * @ORM\OneToMany(targetEntity=ProfilJob::class, mappedBy="user")
     */
    private $profilJobs;


    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->avatar = 'images_default/default_image.jpg';
        $this->votes = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->userVotes = new ArrayCollection();
        $this->birthday = new \DateTime();
        $this->blogs = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->profilJobs = new ArrayCollection();
 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Boutique[]
     */
    public function getBoutiques(): Collection
    {
        return $this->boutiques;
    }

    public function addBoutique(Boutique $boutique): self
    {
        if (!$this->boutiques->contains($boutique)) {
            $this->boutiques[] = $boutique;
            $boutique->setUser($this);
        }

        return $this;
    }

    public function removeBoutique(Boutique $boutique): self
    {
        if ($this->boutiques->contains($boutique)) {
            $this->boutiques->removeElement($boutique);
            // set the owning side to null (unless already changed)
            if ($boutique->getUser() === $this) {
                $boutique->setUser(null);
            }
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
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
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
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserVote[]
     */
    public function getUserVotes(): Collection
    {
        return $this->userVotes;
    }

    public function addUserVote(UserVote $userVote): self
    {
        if (!$this->userVotes->contains($userVote)) {
            $this->userVotes[] = $userVote;
            $userVote->setUser($this);
        }

        return $this;
    }

    public function removeUserVote(UserVote $userVote): self
    {
        if ($this->userVotes->contains($userVote)) {
            $this->userVotes->removeElement($userVote);
            // set the owning side to null (unless already changed)
            if ($userVote->getUser() === $this) {
                $userVote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUser($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->contains($blog)) {
            $this->blogs->removeElement($blog);
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
            }
        }

        return $this;
    }

    public function getLastActivityAt(): ?\DateTimeInterface
    {
        return $this->lastActivityAt;
    }

    public function setLastActivityAt(?\DateTimeInterface $lastActivityAt): self
    {
        $this->lastActivityAt = $lastActivityAt;

        return $this;
    }

    /**
     * @return Bool whether the user is active or not
     */

     public function isActiveNow(){
         $delay = new \DateTime("2 minutes ago");

         return ($this->getLastActivityAt()>$delay);
     }

     /**
      * @return Collection|Notification[]
      */
     public function getNotifications(): Collection
     {
         return $this->notifications;
     }

     public function addNotification(Notification $notification): self
     {
         if (!$this->notifications->contains($notification)) {
             $this->notifications[] = $notification;
             $notification->addToUser($this);
         }

         return $this;
     }

     public function removeNotification(Notification $notification): self
     {
         if ($this->notifications->contains($notification)) {
             $this->notifications->removeElement($notification);
             $notification->removeToUser($this);
         }

         return $this;
     }

     public function getConfimation(): ?string
     {
         return $this->confimation;
     }

     public function setConfimation(?string $confimation): self
     {
         $this->confimation = $confimation;

         return $this;
     }

     /**
      * @return Collection|ProfilJob[]
      */
     public function getProfilJobs(): Collection
     {
         return $this->profilJobs;
     }

     public function addProfilJob(ProfilJob $profilJob): self
     {
         if (!$this->profilJobs->contains($profilJob)) {
             $this->profilJobs[] = $profilJob;
             $profilJob->setUser($this);
         }

         return $this;
     }

     public function removeProfilJob(ProfilJob $profilJob): self
     {
         if ($this->profilJobs->removeElement($profilJob)) {
             // set the owning side to null (unless already changed)
             if ($profilJob->getUser() === $this) {
                 $profilJob->setUser(null);
             }
         }

         return $this;
     }

}
