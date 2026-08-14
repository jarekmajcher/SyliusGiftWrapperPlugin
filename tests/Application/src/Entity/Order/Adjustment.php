<?php

declare(strict_types=1);

namespace Tests\JarekMajcher\SyliusGiftWrapperPlugin\Application\src\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Adjustment as BaseAdjustment;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_adjustment")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_adjustment')]
class Adjustment extends BaseAdjustment
{
    use \JarekMajcher\SyliusGiftWrapperPlugin\Model\AdjustmentTrait;

    public function __construct()
    {
        parent::__construct();
    }
}
