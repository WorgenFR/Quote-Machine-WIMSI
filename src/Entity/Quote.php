<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuoteRepository::class)
 */
class Quote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"group1", "group2"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"group1", "group2"})
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"group1", "group2"})
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private $meta;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="quotes")
     * @Groups({"group1", "group2"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="quotes")
     * * @Gedmo\Blameable(on="create")
     * @Groups({"group1", "group2"})
     */
    private $author;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"group1", "group2"})
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="quote")
     * @Groups({"group1", "group2"})
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    /**
     * @Groups("group2")
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups("group2")
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @Groups("group2")
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @Groups("group2")
     */
    public function getMeta(): ?string
    {
        return $this->meta;
    }

    /**
     * @Groups("group2")
     */
    public function setMeta(string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @Groups("group2")
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @Groups("group2")
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @Groups("group2")
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @Groups("group2")
     */
    public function setAuthor(?User $user): self
    {
        $this->author = $user;

        return $this;
    }

    /**
     * @Groups("group2")
     */
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    /**
     * @Groups("group2")
     */
    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection|Like[]
     * @Groups("group2")
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setQuote($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getQuote() === $this) {
                $like->setQuote(null);
            }
        }

        return $this;
    }
}
