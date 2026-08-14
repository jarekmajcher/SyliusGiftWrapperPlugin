<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Taxation\Applicator;

use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\TaxRateInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;

class GiftWrapTaxesApplicator implements OrderTaxesApplicatorInterface
{
    public function __construct(
        private CalculatorInterface $calculator,
        private AdjustmentFactoryInterface $adjustmentFactory,
        private TaxRateResolverInterface $taxRateResolver,
    ) {
    }

    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        foreach ($order->getGiftWraps() as $giftWrap) {
            $giftWrapMethod = $giftWrap->getGiftWrapMethod();

            /** @var TaxRateInterface|null $taxRate */
            $taxRate = $this->taxRateResolver->resolve($giftWrapMethod, ['zone' => $zone]);
            if (null === $taxRate) {
                continue;
            }

            $taxAmount = $this->calculator->calculate($giftWrap->getUnitPrice(), $taxRate);
            if (0.00 === $taxAmount) {
                continue;
            }

            $adjustment = $this->adjustmentFactory->createNew();
            $adjustment->setGiftWrap($giftWrap);
            $adjustment->setType(AdjustmentInterface::TAX_ADJUSTMENT);
            $adjustment->setLabel($taxRate->getLabel(),);
            $adjustment->setAmount((int) $taxAmount);
            $adjustment->setNeutral($taxRate->isIncludedInPrice());
            $adjustment->setOriginCode('gift_wrap_tax');
            $adjustment->setDetails([
                'giftWrapCode' => $giftWrapMethod->getCode(),
                'giftWrapName' => $giftWrapMethod->getName(),
                'taxRateCode' => $taxRate->getCode(),
                'taxRateName' => $taxRate->getName(),
                'taxRateAmount' => $taxRate->getAmount(),
            ]);

            $order->addAdjustment($adjustment);
        }
    }
}
