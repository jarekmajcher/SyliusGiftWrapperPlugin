<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Fixture\Factory;

use Faker\Generator;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethod;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethodImage;
use JarekMajcher\SyliusGiftWrapperPlugin\Entity\GiftWrapMethodTranslation;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class GiftWrapMethodExampleFactory implements ExampleFactoryInterface
{
    private OptionsResolver $optionsResolver;

    private Generator $faker;

    /** @var string[] */
    private ?array $availableImages = [];

    public function __construct(
        private FactoryInterface $giftWrapMethodFactory,
        private FactoryInterface $giftWrapMethodTranslationFactory,
        private ChannelRepositoryInterface $channelRepository,
        private RepositoryInterface $localeRepository,
        private FactoryInterface $imageFactory,
        private ImageUploaderInterface $imageUploader,
        private string $imagesDirectory,
    ) {
        $this->faker = \Faker\Factory::create();

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): GiftWrapMethod
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var GiftWrapMethod $giftWrapMethod */
        $giftWrapMethod = $this->giftWrapMethodFactory->createNew();

        $giftWrapMethod->setCode($options['code']);
        $giftWrapMethod->setEnabled($options['enabled']);
        $giftWrapMethod->setPrice($options['price']);
        $giftWrapMethod->setName($options['name']);

        foreach ($options['channels'] as $channel) {
            $giftWrapMethod->addChannel($channel);
        }

        foreach ($options['translations'] as $localeCode => $translation) {
            /** @var GiftWrapMethodTranslation $giftWrapMethodTranslation */
            $giftWrapMethodTranslation = $this->giftWrapMethodTranslationFactory->createNew();

            $giftWrapMethodTranslation->setLocale($localeCode);
            $giftWrapMethodTranslation->setName($translation['name']);
            $giftWrapMethodTranslation->setDescription($translation['description']);

            $giftWrapMethod->addTranslation($giftWrapMethodTranslation);
        }

        foreach ($options['images'] as $imagePath) {
            $giftWrapMethod->addImage($this->createImage($imagePath));
        }

        return $giftWrapMethod;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('code', function (Options $options): string {
                return $this->faker->unique()->numerify('gift_##########');
            })

            ->setDefault('name', function (Options $options): string {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('price', function (Options $options): int {
                return $this->faker->numberBetween(100, 2000);
            })

            ->setAllowedTypes('price', 'int')
            ->setDefault('enabled', function (Options $options): bool {
                return $this->faker->boolean(80);
            })

            ->setAllowedTypes('enabled', 'bool')

            ->setDefault('channels', LazyOption::randomOnes($this->channelRepository, 3))
            ->setAllowedTypes('channels', 'array')
            ->setNormalizer('channels', LazyOption::findBy($this->channelRepository, 'code'))

            ->setDefault('translations', function (OptionsResolver $translationResolver): void {
                $translationResolver->setDefaults($this->configureDefaultTranslations());
            })

            ->setDefault('images', function (Options $options): array {
                return $this->findDefaultImagePaths();
            })
            ->setAllowedTypes('images', 'array')
        ;
    }

    private function configureDefaultTranslations(): array
    {
        $translations = [];
        $locales = $this->localeRepository->findAll();

        /** @var LocaleInterface $locale */
        foreach ($locales as $locale) {
            $name = ucfirst($this->faker->words(3, true));
            $description = ucfirst($this->faker->sentence(20, true));
            $translations[$locale->getCode()] = [
                'name' => $name,
                'description' => $description,
            ];
        }

        return $translations;
    }


    private function findDefaultImagePaths(): array
    {
        if ([] === $this->availableImages) {
            $files = array_filter(
                glob($this->imagesDirectory . '*.jpg'),
                static fn (string $file): bool => !in_array(basename($file), ['gift-eco.jpg', 'gift-premium.jpg'], true)
            );

            if ([] === $files) {
                return [];
            }

            shuffle($files);

            $this->availableImages = $files;
        }

        $imagePath = array_shift($this->availableImages);

        return [$imagePath];
    }

    private function createImage(string $imagePath): GiftWrapMethodImage
    {
        Assert::fileExists($imagePath, sprintf('Gift wrap method image "%s" does not exist.', $imagePath));

        /** @var GiftWrapMethodImage $image */
        $image = $this->imageFactory->createNew();
        $image->setFile(new UploadedFile($imagePath, basename($imagePath), null, null, true));
        $image->setType('main');

        $this->imageUploader->upload($image);

        return $image;
    }
}