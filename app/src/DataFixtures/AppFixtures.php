<?php

namespace App\DataFixtures;


use AllowDynamicProperties;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Entity\Subjects;
use App\Entity\Classes;
use App\Entity\Courses;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }

    public function setSubjects(?subjects $subjects): self
    {
        $this->subjects = $subjects;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Users
        for ($i = 0; $i <= 10; $i++) {
            $user = new Users();
            $user->setEmail($faker->unique()->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setRegisterAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));
            $manager->persist($user);
        }

        // Subjects
        $subjectLabels = [
            'informatique',
            'electronique',
            'mathematique',
            'culture_generale'
        ];

        foreach ($subjectLabels as $index => $key) {
            $subject = new Subjects();
            $subject->setName(ucfirst(str_replace('_', ' ', $key)));
            $manager->persist($subject);
            $this->addReference('subject_' . $index, $subject);
        }

        // Classes
        $classData = [
            ['name' => '6e A', 'level' => 6],
            ['name' => '5e B', 'level' => 5],
            ['name' => '4e C', 'level' => 4],
            ['name' => '3e A', 'level' => 3],
            ['name' => '3e B', 'level' => 3],
        ];

        foreach ($classData as $index => $data) {
            $classRoom = new Classes();
            $classRoom->setName($data['name'])
                ->setLevel($data['level']);
            $manager->persist($classRoom);
            $this->addReference('classRoom_' . $index, $classRoom);
        }

        $manager->flush();
    }
}
