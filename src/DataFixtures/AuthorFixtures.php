<?php


namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AuthorFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("fr_CA");
        $faker->seed(150);

        for($i = 0; $i < 25; $i++) {
            $author = new Author();
            $gender = mt_rand(1, 10) >= 5 ? "female" : "male";
            $author ->setName($faker->lastName)
                    ->setFirstName($faker->firstName($gender))
                    ->setGender(substr($gender,0, 1))
                    ->setBirthDate($faker->dateTimeThisCentury);
            $manager->persist($author);
            $this->addReference("author_$i", $author);
        }

        $manager->flush();
    }
}