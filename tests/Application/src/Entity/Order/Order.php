<?php

declare(strict_types=1);

namespace Tests\JarekMajcher\SyliusGiftWrapperPlugin\Application\src\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Order as BaseOrder;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_order')]
class Order extends BaseOrder
{
    use \JarekMajcher\SyliusGiftWrapperPlugin\Model\OrderTrait;

    public function __construct()
    {
        parent::__construct();

        $this->initGiftWraps();
    }
}
