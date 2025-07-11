<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $coefficient = null;

    #[ORM\Column(length: 10)]
    private ?string $day = null;

    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(length: 10)]
    private ?string $room = null;

    /**
     * @var Collection<int, Ressources>
     */
    #[ORM\ManyToMany(targetEntity: Ressources::class, mappedBy: 'courses')]
    private Collection $ressources;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subjects $subjects = null;

    /**
     * @var Collection<int, Results>
     */
    #[ORM\OneToMany(targetEntity: Results::class, mappedBy: 'courses')]
    private Collection $results;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'courses')]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classes $classes = null;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'courses')]
    private Collection $teachers;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->startAt = new \DateTimeImmutable();
        $this->endAt = new \DateTimeImmutable();
        $this->teachers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(string $room): static
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection<int, Ressources>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressources $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->addCourse($this);
        }

        return $this;
    }

    public function removeRessource(Ressources $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            $ressource->removeCourse($this);
        }

        return $this;
    }

    public function getSubjects(): ?Subjects
    {
        return $this->subjects;
    }

    public function setSubjects(?Subjects $subjects): static
    {
        $this->subjects = $subjects;

        return $this;
    }

    /**
     * @return Collection<int, Results>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Results $result): static
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setCourses($this);
        }

        return $this;
    }

    public function removeResult(Results $result): static
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getCourses() === $this) {
                $result->setCourses(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addCourse($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeCourse($this);
        }

        return $this;
    }

    public function getClasses(): ?Classes
    {
        return $this->classes;
    }

    public function setClasses(?Classes $classes): static
    {
        $this->classes = $classes;

        return $this;
    }

    // Ajout personnel
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Users $teacher): static
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers[] = $teacher;
        }

        return $this;
    }

    public function removeTeacher(Users $teacher): static
    {
        $this->teachers->removeElement($teacher);

        return $this;
    }
}
