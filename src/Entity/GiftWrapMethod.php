<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JarekMajcher\SyliusGiftWrapperPlugin\Repository\GiftWrapMethodRepository;
use Sylius\Component\Taxation\Model\TaxableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Sylius\Component\Core\Model\ImageInterface;

#[ORM\Entity(repositoryClass: GiftWrapMethodRepository::class)]
#[ORM\Table(name: 'majcher_gift_wrapper_gift_wrap_method')]
#[UniqueEntity(fields: ['code'])]
class GiftWrapMethod implements TranslatableInterface, ResourceInterface, TaxableInterface, ImagesAwareInterface
{
    use TranslatableTrait {
        TranslatableTrait::__construct as private initializeTranslationsCollection;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $enabled = true;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $name = '';

    #[ORM\Column(type: Types::INTEGER)]
    private int $position = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private int $price = 0;

    #[ORM\ManyToOne(targetEntity: TaxCategoryInterface::class)]
    #[ORM\JoinColumn(name: "tax_category_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?TaxCategoryInterface $taxCategory = null;

    #[ORM\ManyToMany(targetEntity: ChannelInterface::class)]
    #[ORM\JoinTable(name: 'majcher_gift_wrapper_gift_wrap_method_channels')]
    #[ORM\JoinColumn(name: 'gift_wrap_method_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'channel_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Collection $channels;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: GiftWrapMethodImage::class, cascade: ['all'], orphanRemoval: true)]
    protected Collection $images;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->initializeTranslationsCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getTaxCategory(): ?TaxCategoryInterface
    {
        return $this->taxCategory;
    }

    public function setTaxCategory(?TaxCategoryInterface $taxCategory): void
    {
        $this->taxCategory = $taxCategory;
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    protected function createTranslation(): GiftWrapMethodTranslation
    {
        return new GiftWrapMethodTranslation();
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getImagesByType(string $type): Collection
    {
        return $this->images->filter(fn(ImageInterface $image) => $image->getType() === $type);
    }

    public function hasImages(): bool
    {
        return !$this->images->isEmpty();
    }

    public function hasImage(ImageInterface $image): bool
    {
        return $this->images->contains($image);
    }

    public function addImage(ImageInterface $image): void
    {
        if (!$this->hasImage($image)) {
            $image->setOwner($this);
            $this->images->add($image);
        }
    }

    public function removeImage(ImageInterface $image): void
    {
        if ($this->hasImage($image)) {
            $image->setOwner(null);
            $this->images->removeElement($image);
        }
    }

    public function getMainImage(): ?ImageInterface {
        $mainImage = $this->getImagesByType('main')->first();
        if(false !== $mainImage) {
            return $mainImage;
        }

        $firstImage = $this->getImages()->first();
        if(false !== $firstImage) {
            return $firstImage;
        }

        return null;
    }
}
