<?php

namespace JarekMajcher\SyliusGiftWrapperPlugin\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMainMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->getChild('catalog')
            ->addChild('gift', [
                'route' => 'jarekmajcher_sylius_gift_wrapper_plugin_admin_gift_wrap_method_index'
            ])
            ->setLabel('jarekmajcher_sylius_gift_wrapper_plugin.ui.gift_wrap_methods')
            ->setLabelAttribute('icon', 'gift');
    }
}
