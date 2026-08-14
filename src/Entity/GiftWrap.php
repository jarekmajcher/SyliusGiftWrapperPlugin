<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'majcher_gift_wrapper_gift_wrap')]
class GiftWrap extends AbstractTranslation implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderInterface::class, inversedBy: "giftWraps")]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderInterface $order = null;

    #[ORM\ManyToOne(targetEntity: GiftWrapMethod::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?GiftWrapMethod $giftWrapMethod = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $unitPrice = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dedication = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $instruction = null;

    #[ORM\OneToMany(targetEntity: AdjustmentInterface::class, mappedBy: "giftWrap", cascade: ["persist", "remove"])]
    private Collection $adjustments;

    public function __construct()
    {
        $this->adjustments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getGiftWrapMethod(): ?GiftWrapMethod
    {
        return $this->giftWrapMethod;
    }

    public function setGiftWrapMethod(?GiftWrapMethod $giftWrapMethod): void
    {
        $this->giftWrapMethod = $giftWrapMethod;
    }

    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

    public function getDedication(): ?string
    {
        return $this->dedication;
    }

    public function setDedication(?string $dedication): void
    {
        $this->dedication = $dedication;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(?string $instruction): void
    {
        $this->instruction = $instruction;
    }

    public function getAdjustments(?string $type = null): Collection
    {
        if (null === $type) {
            return $this->adjustments;
        }

        return $this->adjustments->filter(function (AdjustmentInterface $adjustment) use ($type): bool {
            return $type === $adjustment->getType();
        });
    }

    public function setAdjustments(Collection $adjustments): void
    {
        $this->adjustments = $adjustments;
    }
}
