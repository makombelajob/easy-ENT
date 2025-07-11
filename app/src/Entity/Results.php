<?php

namespace App\Entity;

use App\Repository\ResultsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultsRepository::class)]
class Results
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(nullable: true)]
    private ?int $anualNote = null;

    #[ORM\Column(nullable: true)]
    private ?int $mensualNote = null;

    #[ORM\Column(length: 255)]
    private ?string $remark = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Courses $courses = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAnualNote(): ?int
    {
        return $this->anualNote;
    }

    public function setAnualNote(?int $anualNote): static
    {
        $this->anualNote = $anualNote;

        return $this;
    }

    public function getMensualNote(): ?int
    {
        return $this->mensualNote;
    }

    public function setMensualNote(?int $mensualNote): static
    {
        $this->mensualNote = $mensualNote;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): static
    {
        $this->remark = $remark;

        return $this;
    }

    public function getCourses(): ?Courses
    {
        return $this->courses;
    }

    public function setCourses(?Courses $courses): static
    {
        $this->courses = $courses;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }
}
