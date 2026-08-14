<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Form\Type;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethodImage;
use Sylius\Bundle\CoreBundle\Form\Type\ImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GiftWrapMethodImageType extends ImageType
{
    public function __construct()
    {
        parent::__construct(GiftWrapMethodImage::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => GiftWrapMethodImage::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'jarekmajcher_sylius_gift_wrapper_plugin_gift_wrap_method_image';
    }
}
