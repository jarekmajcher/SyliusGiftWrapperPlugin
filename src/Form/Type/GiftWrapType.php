<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Form\Type;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrap;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class GiftWrapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('giftWrapMethod', EntityType::class, [
                'class' => GiftWrapMethod::class,
                'choices' => $options['gift_wrap_methods'],
                'choice_label' => 'code',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'empty_data' => null,
                'constraints' => [
                    new NotBlank(['message' => 'jarekmajcher_sylius_gift_wrapper_plugin.gift_wrap_method.empty']),
                ]
            ])
            ->add('dedication', TextareaType::class, [
                'required' => false,
                'label' => 'jarekmajcher_sylius_gift_wrapper_plugin.ui.dedication',
                'attr' => [
                    'rows' => 5,
                    'style' => 'resize: none;',
                ],
                'help' => 'jarekmajcher_sylius_gift_wrapper_plugin.ui.dedication_help'
            ])
            ->add('instruction', TextareaType::class, [
                'required' => false,
                'label' => 'jarekmajcher_sylius_gift_wrapper_plugin.ui.instruction',
                'attr' => [
                    'rows' => 5,
                    'style' => 'resize: none;',
                ],
                'help' => 'jarekmajcher_sylius_gift_wrapper_plugin.ui.instruction_help'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GiftWrap::class,
            'csrf_protection' => true,
        ]);

        $resolver->setRequired('gift_wrap_methods');
        $resolver->setAllowedTypes('gift_wrap_methods', 'array');
    }

    public function getBlockPrefix(): string
    {
        return 'jarekmajcher_sylius_gift_wrapper_plugin_gift_wrap';
    }
}