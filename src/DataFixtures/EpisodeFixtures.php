<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $input;

    public function __construct(Slugify $input)
    {
        $this->input = $input;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=0; $i <= 135 ; $i++) { 
            $episode = new Episode();
            $episode->setTitle($faker->word());
            $episode->setSlug($this->input->generate($episode->getTitle()));
            $episode->setNumber($faker->numberBetween(1, 3));
            $episode->setSynopsis($faker->paragraph());
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween(0, 44)));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}