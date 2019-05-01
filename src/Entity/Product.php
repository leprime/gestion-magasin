<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="produits")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mark;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Output",
     *     mappedBy="produit",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     * @ORM\OrderBy({"outputed_at": "DESC"})
     */
    private $outputs;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Entry",
     *     mappedBy="product",
     *     orphanRemoval=true,
     *     cascade={"persist"})
     */
    private $entries;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $service;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refe_order;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove", "merge"})
     * @ORM\JoinColumn(name="cover_id", referencedColumnName="id", nullable=true)
     **/
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExitPermit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLendable;

    public function __construct()
    {
        $this->outputs = new ArrayCollection();
        $this->entries = new ArrayCollection();
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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return Collection|Output[]
     */
    public function getOutputs(): Collection
    {
        return $this->outputs;
    }

    public function addOutput(Output $output): self
    {
        if (!$this->outputs->contains($output)) {
            $this->outputs[] = $output;
            $output->setProduit($this);
        }

        return $this;
    }

    public function removeOutput(Output $output): self
    {
        if ($this->outputs->contains($output)) {
            $this->outputs->removeElement($output);
            // set the owning side to null (unless already changed)
            if ($output->getProduit() === $this) {
                $output->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Entry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setProduct($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): self
    {
        if ($this->entries->contains($entry)) {
            $this->entries->removeElement($entry);
            // set the owning side to null (unless already changed)
            if ($entry->getProduct() === $this) {
                $entry->setProduct(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getRefeOrder(): ?string
    {
        return $this->refe_order;
    }

    public function setRefeOrder(string $refe_order): self
    {
        $this->refe_order = $refe_order;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image $cover
     */
    public function setImage(Image $cover): void
    {
        $this->image = $cover;
    }

    public function getIsExitPermit(): ?bool
    {
        return $this->isExitPermit;
    }

    public function setIsExitPermit(bool $isExitPermit): self
    {
        $this->isExitPermit = $isExitPermit;

        return $this;
    }

    public function getIsLendable(): ?bool
    {
        return $this->isLendable;
    }

    public function setIsLendable(bool $isLendable): self
    {
        $this->isLendable = $isLendable;

        return $this;
    }

}
