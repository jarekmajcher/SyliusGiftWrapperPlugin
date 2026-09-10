<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Fixture;

use JarekMajcher\SyliusGiftWrapperPlugin\Fixture\Factory\GiftWrapMethodExampleFactory;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class GiftWrapMethodFixture extends AbstractResourceFixture
{
    public function __construct(EntityManagerInterface $pageManager, GiftWrapMethodExampleFactory $giftWrapMethodExampleFactory)
    {
        parent::__construct($pageManager, $giftWrapMethodExampleFactory);
    }

    public function getName(): string
    {
        return 'jarekmajcher_gift_wrap_method';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('code')->end()
                ->scalarNode('name')->end()
                ->booleanNode('enabled')->end()
                ->integerNode('position')->end()
                ->arrayNode('channels')->scalarPrototype()->end()->end()
                ->integerNode('price')->end()
                ->arrayNode('translations')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('description')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('images')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }
}
