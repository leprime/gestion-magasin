<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sorties")
 * @ORM\Entity(repositoryClass="App\Repository\OutputRepository")
 */
class Output
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Expression(
     *     "this.getQuantity() < this.getProduit().getQuantity()",
     *     message="Quantitté sortie trop élevée que le stock"
     * )
     * @Assert\GreaterThanOrEqual(message="Quantitté minimum 1", value=1)
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="outputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Assert\DateTime
     */
    private $outputed_at;

    /**
     * @ORM\Column(type="text")
     */
    private $observation;

    /**
     * Output constructor.
     */
    public function __construct()
    {
        $this->outputed_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduit(): ?Product
    {
        return $this->produit;
    }

    public function setProduit(Product $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getOutputedAt(): ?\DateTimeInterface
    {
        return $this->outputed_at;
    }

    public function setOutputedAt(\DateTimeInterface $created_at): self
    {
        $this->outputed_at = $created_at;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

}
