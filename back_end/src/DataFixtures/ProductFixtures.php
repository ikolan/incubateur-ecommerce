<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\TagsRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var TagsRepository
     */
    private $tagsRepository;

    public function __construct(CategoryRepository $categoryRepository, TagsRepository $tagsRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->tagsRepository = $tagsRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $productnames = [
            'Asus ROG Strix G15 (G513QE-HN009T)',
            'Asus ROG Strix G15 (G513QE-HN009T)',
            'Asus ROG Strix G15 (G513QE-HN009T)',
            'Asus ROG Strix G15 (G513QE-HN009T)',
            'PC Gamer ARGON - Avec Windows',
            'PC Gamer ARGON - Avec Windows',
            'PC Gamer ARGON - Avec Windows',
            'Alienware Aurora (R13-583)',
            'Alienware Aurora (R13-583)',
            'Alienware Aurora (R13-583)',
            'MSI MPG Trident A (11TC-1819EU)',
            'MSI MPG Trident A (11TC-1819EU)',
            'MSI MPG Trident A (11TC-1819EU)',
            'MSI MPG Trident A (11TC-1819EU)',
            'Dell G15 (5510-936)',
            'Dell G15 (5510-936)',
            'Dell G15 (5510-936)',
            'Dell G15 (5510-936)',
            'Dell G15 (5510-936)',
            'Acer Aspire 3 (A317-33-P9DS) Gris',
            'Acer Aspire 3 (A317-33-P9DS) Gris',
            'Acer Aspire 3 (A317-33-P9DS) Gris',
            'Acer Aspire 3 (A317-33-P9DS) Gris',
            'Asus Vivobook S15 (S533EA-L11045T)',
            'Asus Vivobook S15 (S533EA-L11045T)',
            'Asus Vivobook S15 (S533EA-L11045T)',
            'Asus Vivobook S15 (S533EA-L11045T)',
            'Asus Vivobook S15 (S533EA-L11045T)',
            'PC EKONOM',
            'PC EKONOM',
            'PC EKONOM',
            'PC EKONOM',
            'PC EKONOM',
            'Asus ExpertCenter X5 SFF (X500MA-R4700G006R)',
            'Asus ExpertCenter X5 SFF (X500MA-R4700G006R)',
            'Asus ExpertCenter X5 SFF (X500MA-R4700G006R)',
            'Asus ExpertCenter X5 SFF (X500MA-R4700G006R)',
            'Asus ExpertCenter X5 SFF (X500MA-R4700G006R)',
            'PC Gamer RYZENBOW - Sans Windows',
            'PC Gamer RYZENBOW - Sans Windows',
            'PC Gamer RYZENBOW - Sans Windows',
            'PC Gamer RYZENBOW - Sans Windows',
            'PC Gamer RYZENBOW - Sans Windows',
        ];

        $prices = [
            555.99,
            689.99,
            123,
            350,
            649.49,
            1099.99,
            1599.99,
            1200.9,
            2599.99,
            1150,
        ];

        $taxes = [
            2,
            4,
            5,
            10,
            15,
        ];

        $descriptions = [
            'Avec cet ordinateur, la bureautique n\aura jamais été plus agréable !',
            'Une machine de guerre prête à tout endurer !',
            'Youtube en 4K no problem easy peezy lemon squeezy',
            'Suffisant pour jouer à Minecraft',
            'La puissance dans un format réduit',
            'La bureautique partout ou vous allez',
            'Avec ce PC portable, vous pourrez voyager sans craindre une batterie à plat',
            'Tous les derniers jeux sortis contenus dans une si petite machine',
            'Réveillez le gamer qui est en vous',
            'Votre imagination ne connaîtra plus de limite',
        ];

        $detailedDescription = [
            "Avec ce PC gamer et sa mémoire embarquée, vous n'aurez plus de souci à vous faire lors de vos sessions de jeu intensives. En effet, grâce à sa carte graphique puissante et son processeur embarqué capable de grosses performances, vous bénéficierez jusqu'à 120 images par seconde en 4K non stop.",
            "Pour la bureautique, cet ordinateur sera parfait. Grâce à sa mémoire embarquée, vous n'aurez pas de problème de ralentissement et pourrez aisément stocker tous vos fichiers, photos, vidéos et souvenir dans le disque dur, sans avoir peur d'arriver à court de stockage.",
        ];

        $weights = [
            5,
            15,
            25,
            30,
            20,
        ];

        $stocks = [
            0,
            5,
            10,
            15,
            20,
            25,
            50,
            100,
            150,
            250,
        ];

        $categories = $this->categoryRepository->findAll();
        $tags = $this->tagsRepository->findAll();

        foreach ($productnames as $productName) {
            foreach ($categories as $category) {
                $product = new Product();

                $product->setCategory($category);
                $product->setName($productName);

                // Generate random tags
                $tagsnbr = random_int(0, 6);
                for ($j = 0; $j < $tagsnbr; ++$j) {
                    $product->addTag($tags[random_int(0, (is_array($tags) || $tags instanceof \Countable ? count($tags) : 0) - 1)]);
                }

                $reference = str_replace(' ', '_', str_split($productName, 4)[0]).'Ref-'.$this->generateEndProductRef();
                $product->setReference($reference);

                $product->setPrice($prices[random_int(0, count($prices) - 1)] * 100);
                $product->setTax($taxes[random_int(0, count($taxes) - 1)]);
                $product->setDescription($descriptions[random_int(0, count($descriptions) - 1)]);
                $product->setDetailedDescription($detailedDescription[random_int(0, count($detailedDescription) - 1)]);
                $product->setWeight($weights[random_int(0, count($weights) - 1)]);
                $product->setStock($stocks[random_int(0, count($stocks) - 1)]);
                $product->setAddedAt(new \DateTimeImmutable('now'));
                $product->setFrontPage((1 == random_int(0, 1)));

                $manager->persist($product);
            }
        }

        // for ($i = 0; $i < 50; ++$i) {
        //     $productname = $productnames[random_int(0, count($productnames) - 1)];

        //     $product = new Product();

        //     // Generate random category
        //     $product->setCategory($categories[random_int(0, (is_array($categories) || $categories instanceof \Countable ? count($categories) : 0) - 1)]);
        //     $product->setName($productname);

        //     // Generate random tags
        //     $tagsnbr = random_int(0, 6);
        //     for ($j = 0; $j < $tagsnbr; ++$j) {
        //         $product->addTag($tags[random_int(0, (is_array($tags) || $tags instanceof \Countable ? count($tags) : 0) - 1)]);
        //     }

        //     $reference = str_replace(' ', '_', str_split($productname, 4)[0]).'Ref-'.$this->generateEndProductRef();
        //     $product->setReference($reference);

        //     $product->setPrice($prices[random_int(0, count($prices) - 1)]);
        //     $product->setTax($taxes[random_int(0, count($taxes) - 1)]);
        //     $product->setDescription($descriptions[random_int(0, count($descriptions) - 1)]);
        //     $product->setDetailedDescription($detailedDescription[random_int(0, count($detailedDescription) - 1)]);
        //     $product->setWeight($weights[random_int(0, count($weights) - 1)]);
        //     $product->setStock($stocks[random_int(0, count($stocks) - 1)]);
        //     $product->setAddedAt(new \DateTimeImmutable('now'));
        //     $product->setFrontPage((1 == random_int(0, 1)));

        //     $manager->persist($product);
        // }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'Ordertest'];
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class, TagFixtures::class];
    }

    public function generateEndProductRef()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 15; ++$i) {
            $index = random_int(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
