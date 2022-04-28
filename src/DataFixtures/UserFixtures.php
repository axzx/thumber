<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $datum) {
            $user = new User();
            $user->setName($datum['name'])
                ->setPhoto($datum['photo'] ?? null)
                ->setIsPremium($datum['isPremium'] ?? false)
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array{
     *     array{
     *      name: string,
     *      isPremium: bool|null,
     *      photo: string|null
     *     }
     * }
     */
    private function getData(): array
    {
        return [
            ['name' => 'Jan', 'photo' => 'photos/image1.jpg', 'isPremium' => true],
            ['name' => 'Marian', 'photo' => 'photos/image2.jpg', 'isPremium' => true],
            ['name' => 'Zygmunt', 'photo' => 'photos/image3.jpg', 'isPremium' => true],
            ['name' => 'Janusz', 'photo' => 'photos/image4.jpg'],
            ['name' => 'Borys'],
            ['name' => 'Krystian', 'photo' => 'photos/image5.jpg'],
            ['name' => 'Adrian', 'photo' => 'photos/image6.jpg'],
            ['name' => 'Konrad', 'photo' => 'photos/image7.jpg'],
            ['name' => 'Jacek', 'photo' => 'photos/05/06/image1.jpg'],
            ['name' => 'Antoni', 'photo' => 'photos/01/02/image1.jpg'],
        ];
    }
}
