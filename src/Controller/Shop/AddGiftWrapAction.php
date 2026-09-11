<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Controller\Shop;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrap;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use JarekMajcher\SyliusGiftWrapperPlugin\Form\Type\GiftWrapType;
use JarekMajcher\SyliusGiftWrapperPlugin\Repository\GiftWrapMethodRepository;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddGiftWrapAction extends AbstractController
{
    public function __construct(
        private CartContextInterface $cartContext,
        private ChannelContextInterface $channelContext,
        private GiftWrapMethodRepository $giftWrapMethodRepository,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private TranslatorInterface $translator
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $cart = $this->cartContext->getCart();
        $channel = $this->channelContext->getChannel();

        $giftWrapMethods = $this->giftWrapMethodRepository->findEnabledByChannel($channel);

        if ($giftWrapMethods === []) {
            $this->addFlash('error', 'jarekmajcher_sylius_gift_wrapper_plugin.gift_wrap.no_methods_available');

            return new Response('');
        }

        $giftWrap = new GiftWrap();

        $form = $this->createForm(GiftWrapType::class, $giftWrap, [
            'gift_wrap_methods' => $giftWrapMethods,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                /** @var GiftWrapMethod $giftWrapMethod  */
                $giftWrapMethod = $giftWrap->getGiftWrapMethod();
                $price = $giftWrapMethod->getPrice();

                $giftWrap->setUnitPrice($price);

                $cart->addGiftWrap($giftWrap);

                $this->entityManager->persist($cart);

                $this->eventDispatcher->dispatch(new GenericEvent($cart), SyliusCartEvents::CART_CHANGE);

                $this->entityManager->flush();

                $this->addFlash('success', 'jarekmajcher_sylius_gift_wrapper_plugin.gift_wrap.add_to_cart.success');
            }
            else {
                $reasons = [];

                foreach ($form->getErrors(true) as $error) {
                    $reasons[] = lcfirst($this->translator->trans(
                        $error->getMessageTemplate(),
                        $error->getMessageParameters(),
                        'validators'
                    ));
                }

                $this->addFlash('error', [
                    'message' => 'jarekmajcher_sylius_gift_wrapper_plugin.gift_wrap.add_to_cart.error',
                    'parameters' => [
                        '%reason%' => implode(', ', $reasons),
                    ],
                ]);
            }

            return $this->redirectToRoute('sylius_shop_cart_summary');
        }

        return $this->render('@JarekMajcherSyliusGiftWrapperPlugin/Shop/Cart/add.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart,
        ]);
    }
}