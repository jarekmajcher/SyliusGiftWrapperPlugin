<?php

namespace JarekMajcher\SyliusGiftWrapperPlugin\Model;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait OrderTrait
{
    #[ORM\OneToMany(
        mappedBy: 'order',
        targetEntity: GiftWrap::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $giftWraps;

    public function initGiftWraps(): void
    {
        $this->giftWraps = new ArrayCollection();
    }

    public function hasGiftWraps(): bool
    {
        return !$this->giftWraps->isEmpty();
    }

    public function getGiftWraps(): Collection
    {
        return $this->giftWraps;
    }

    public function addGiftWrap(GiftWrap $giftWrap): self
    {
        if (!$this->giftWraps->contains($giftWrap)) {
            $this->giftWraps->add($giftWrap);
            $giftWrap->setOrder($this);
        }

        return $this;
    }

    public function removeGiftWrap(GiftWrap $giftWrap): self
    {
        if ($this->giftWraps->removeElement($giftWrap)) {
            if ($giftWrap->getOrder() === $this) {
                $giftWrap->setOrder(null);
            }
        }

        return $this;
    }

    public function getGiftWrapsTotal(): int
    {
        $items = $this->getGiftWraps();

        $total = 0;

        foreach ($items as $item) {
            $total += $item->getUnitPrice();
        }

        return $total;
    }
}
