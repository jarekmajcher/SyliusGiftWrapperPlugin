<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Form\Type;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\CoreBundle\Form\Type\Taxon\TaxonImageType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;

final class GiftWrapMethodType extends AbstractResourceType
{
    public function __construct()
    {
        parent::__construct(GiftWrapMethod::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius.ui.code',
                'disabled' => null !== $builder->getData()->getCode(),
            ])
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name'
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'label' => 'sylius.ui.channels',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'sylius.ui.price',
            ])
            ->add('taxCategory', TaxCategoryChoiceType::class, [
                'required' => false,
                'placeholder' => '---',
                'label' => 'sylius.form.product_variant.tax_category',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'ui.translations',
                'entry_type' => GiftWrapMethodTranslationType::class,
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => GiftWrapMethodImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'sylius.ui.images',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'jarekmajcher_sylius_gift_wrapper_plugin_gift_wrap_method';
    }
}
