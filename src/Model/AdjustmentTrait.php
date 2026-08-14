<?php

namespace JarekMajcher\SyliusGiftWrapperPlugin\Model;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrap;
use Doctrine\ORM\Mapping as ORM;

trait AdjustmentTrait
{
    #[ORM\ManyToOne(targetEntity: GiftWrap::class, inversedBy: "adjustments")]
    #[ORM\JoinColumn(name: "gift_wrap_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected ?GiftWrap $giftWrap = null;

    public function getGiftWrap(): ?GiftWrap
    {
        return $this->giftWrap;
    }

    public function setGiftWrap(?GiftWrap $giftWrap): void
    {
        $this->giftWrap = $giftWrap;
    }
}
