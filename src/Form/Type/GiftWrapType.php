<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrap;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class GiftWrapType extends AbstractType
{
    public function __construct(
        private ChannelContextInterface $channelContext,
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $channel = $this->channelContext->getChannel();

        $builder
            ->add('giftWrapMethod', EntityType::class, [
                'class' => GiftWrapMethod::class,
                'query_builder' => function (EntityRepository $er) use ($channel): QueryBuilder {
                    return $er->createQueryBuilder('gwm')
                        ->andWhere(':channel MEMBER OF gwm.channels')
                        ->andWhere('gwm.enabled = true')
                        ->setParameter('channel', $channel);
                },
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
    }

    public function getBlockPrefix(): string
    {
        return 'jarekmajcher_sylius_gift_wrapper_plugin_gift_wrap';
    }
}
