<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Controller\Shop;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrap;
use Sylius\Component\Order\Context\CartContextInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class RemoveGiftWrapAction extends AbstractController
{
    public function __construct(
        private CartContextInterface $cartContext,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(Request $request, int $id): Response
    {
        $cart = $this->cartContext->getCart();
        $giftWrap = $this->entityManager->getRepository(GiftWrap::class)->find($id);

        if ($giftWrap === null) {
            throw $this->createNotFoundException();
        }

        if ($giftWrap->getOrder() !== $cart) {
            throw $this->createAccessDeniedException();
        }

        $cart->removeGiftWrap($giftWrap);

        $this->eventDispatcher->dispatch(new GenericEvent($cart), SyliusCartEvents::CART_CHANGE);

        $this->entityManager->flush();

        $this->addFlash('success', 'jarekmajcher_sylius_gift_wrapper_plugin.gift_wrap.remove_from_cart.success');

        return $this->redirectToRoute('sylius_shop_cart_summary');
    }
}
