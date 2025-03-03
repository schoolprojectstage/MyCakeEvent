<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $orderedAt;

    #[ORM\Column(type: 'string', length: 50)]
    private string $orderStatus = "Commande créée";

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?DateTimeInterface $collectDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ordersToSellers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $buyer;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'orderFromBuyer')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Address $billingAddress;

    #[ORM\Column(type: 'float')]
    private float $total;

    #[ORM\OneToMany(mappedBy: 'orderReference', targetEntity: OrderLine::class, cascade: ['persist', 'remove'])]
    private Collection $orderLines;

    #[ORM\Column(type: 'string', length: 255)]
    private $number;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderedAt(): ?DateTimeInterface
    {
        return $this->orderedAt;
    }

    public function setOrderedAt(DateTimeInterface $orderedAt): self
    {
        $this->orderedAt = $orderedAt;

        return $this;
    }

    public function getCollectDate(): ?DateTimeInterface
    {
        return $this->collectDate;
    }

    public function setCollectDate(?DateTimeInterface $collectDate): self
    {
        $this->collectDate = $collectDate;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?Address $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setOrderReference($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getOrderReference() === $this) {
                $orderLine->setOrderReference(null);
            }
        }

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }
}
