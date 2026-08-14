<?php
namespace JarekMajcher\SyliusGiftWrapperPlugin\EventListener;

use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class GiftWrapMethodImageUploadListener
{
    public function __construct(private ImageUploaderInterface $uploader)
    {
    }

    public function uploadImage(GenericEvent $event): void
    {
        $subject = $event->getSubject();

        if (!$subject instanceof GiftWrapMethod) {
            return;
        }

        foreach ($subject->getImages() as $image) {
            if ($image->hasFile()) {
                $this->uploader->upload($image);
            }
        }
    }
}