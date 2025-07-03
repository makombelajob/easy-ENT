<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Entity\Subjects;
use App\Entity\Classes;
use App\Entity\Courses;
use App\Entity\Results;
use App\Entity\SchoolFees;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Subjects
        $subjectLabels = ['Informatique', 'Electronique', 'Mathématique', 'Culture Générale'];
        $subjects = [];
        foreach ($subjectLabels as $label) {
            $subject = new Subjects();
            $subject->setName($label);
            $manager->persist($subject);
            $subjects[] = $subject;
        }

        // Classes
        $classData = [
            ['name' => '6e A', 'level' => 6],
            ['name' => '5e B', 'level' => 5],
            ['name' => '4e C', 'level' => 4],
            ['name' => '3e A', 'level' => 3],
            ['name' => '3e B', 'level' => 3],
        ];
        $classes = [];
        foreach ($classData as $data) {
            $classRoom = new Classes();
            $classRoom->setName($data['name'])
                ->setLevel($data['level']);
            $manager->persist($classRoom);
            $classes[] = $classRoom;
        }

        // Users (élèves et professeurs)
        $users = [];
        $teachers = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setEmail($faker->unique()->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setRegisterAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));
            // Affecter à une classe
            $user->addClass($faker->randomElement($classes));
            $manager->persist($user);
            $users[] = $user;
        }
        // Professeurs
        for ($i = 0; $i < 3; $i++) {
            $teacher = new Users();
            $teacher->setEmail($faker->unique()->email());
            $teacher->setRoles(['ROLE_TEACHER']);
            $teacher->setPassword($this->passwordHasher->hashPassword($teacher, 'password123'));
            $teacher->setFirstname($faker->firstName());
            $teacher->setLastname($faker->lastName());
            $teacher->setRegisterAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));
            $manager->persist($teacher);
            $teachers[] = $teacher;
        }

        // Courses
        $courses = [];
        for ($i = 0; $i < 8; $i++) {
            $course = new Courses();
            $course->setName($faker->word())
                ->setCoefficient($faker->randomFloat(1, 1, 5))
                ->setDay($faker->dayOfWeek())
                ->setStartAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month', 'now')))
                ->setEndAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '+1 month')))
                ->setRoom($faker->randomElement(['A1', 'B2', 'C3', 'D4']))
                ->setSubjects($faker->randomElement($subjects))
                ->setClasses($faker->randomElement($classes));
            // Ajout d'élèves et de profs
            foreach ($faker->randomElements($users, rand(2, 5)) as $user) {
                $course->addUser($user);
            }
            foreach ($faker->randomElements($teachers, rand(1, 2)) as $teacher) {
                $course->addTeacher($teacher);
            }
            $manager->persist($course);
            $courses[] = $course;
        }

        // Results
        foreach ($users as $user) {
            foreach ($faker->randomElements($courses, rand(2, 4)) as $course) {
                $result = new Results();
                $result->setUsers($user)
                    ->setCourses($course)
                    ->setNote($faker->numberBetween(0, 20))
                    ->setAnualNote($faker->numberBetween(0, 20))
                    ->setMensualNote($faker->numberBetween(0, 20))
                    ->setRemark($faker->sentence());
                $manager->persist($result);
            }
        }

        // SchoolFees
        foreach ($users as $user) {
            for ($i = 0; $i < 2; $i++) {
                $fee = new SchoolFees();
                $fee->setName($faker->word())
                    ->setAmount($faker->numberBetween(100, 1000))
                    ->setSendAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')))
                    ->setUsers($user);
                $manager->persist($fee);
            }
        }

        $manager->flush();
    }
}
