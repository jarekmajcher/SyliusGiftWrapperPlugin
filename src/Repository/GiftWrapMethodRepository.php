<?php

namespace JarekMajcher\SyliusGiftWrapperPlugin\Repository;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Webmozart\Assert\Assert;

class GiftWrapMethodRepository extends EntityRepository
{
    public function findEnabledByChannel(ChannelInterface $channel): array
    {
        $result = $this->createQueryBuilder('gwm')
            ->andWhere(':channel MEMBER OF gwm.channels')
            ->andWhere('gwm.enabled = true')
            ->orderBy('gwm.position', 'ASC')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult();

        Assert::isArray($result);
        Assert::allIsInstanceOf($result, GiftWrapMethod::class);

        return $result;
    }
}
