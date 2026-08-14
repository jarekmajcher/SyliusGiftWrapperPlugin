<?php

namespace JarekMajcher\SyliusGiftWrapperPlugin\Processor;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class GiftWrapOrderProcessor implements OrderProcessorInterface
{
    public function __construct(
        private FactoryInterface $adjustmentFactory
    ) {
    }

    public function process(OrderInterface $order): void
    {
        foreach ($order->getAdjustmentsRecursively('gift_wrap') as $adjustment) {
            $order->removeAdjustment($adjustment);
        }

        foreach ($order->getGiftWraps() as $giftWrap) {
            $giftWrapMethod = $giftWrap->getGiftWrapMethod();

            $adjustment = $this->adjustmentFactory->createNew();
            $adjustment->setGiftWrap($giftWrap);
            $adjustment->setType('gift_wrap');
            $adjustment->setLabel($giftWrapMethod->getName());
            $adjustment->setAmount($giftWrap->getUnitPrice());
            $adjustment->setNeutral(false);
            $adjustment->setOriginCode('gift_wrap');
            $adjustment->setDetails([
                'giftWrapCode' => $giftWrapMethod->getCode(),
                'giftWrapName' => $giftWrapMethod->getName(),
            ]);

            $order->addAdjustment($adjustment);
        }
    }
}
