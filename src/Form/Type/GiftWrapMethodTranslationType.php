<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Form\Type;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethodTranslation;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

final class GiftWrapMethodTranslationType extends AbstractResourceType
{
    public function __construct()
    {
        parent::__construct(GiftWrapMethodTranslation::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'sylius.ui.description',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'jarekmajcher_sylius_gift_wrapper_plugin_gift_wrap_method_translation';
    }
}
