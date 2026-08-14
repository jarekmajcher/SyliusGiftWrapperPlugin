<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class JarekMajcherSyliusGiftWrapperPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->setParameter(
            'jarekmajcher_gift_wrapper.plugin_dir',
            $this->getPath(),
        );
    }
}
