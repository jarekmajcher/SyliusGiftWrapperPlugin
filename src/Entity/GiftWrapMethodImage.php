<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JarekMajcher\SyliusGiftWrapperPlugin\Repository\GiftWrapMethodImageRepository;
use Sylius\Component\Core\Model\Image;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GiftWrapMethodImageRepository::class)]
#[ORM\Table(name: 'majcher_gift_wrapper_gift_wrap_method_image')]
#[UniqueEntity(fields: ['code'])]
class GiftWrapMethodImage extends Image
{
    #[ORM\ManyToOne(targetEntity: GiftWrapMethod::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    protected $owner = null;
}