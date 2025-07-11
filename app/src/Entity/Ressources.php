<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourcesRepository::class)]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $fileName = null;

    #[ORM\Column(length: 10)]
    private ?string $fileType = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $uploadAt = null;

    /**
     * @var Collection<int, Courses>
     */
    #[ORM\ManyToMany(targetEntity: Courses::class, inversedBy: 'ressources')]
    private Collection $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->uploadAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): static
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getUploadAt(): ?\DateTimeImmutable
    {
        return $this->uploadAt;
    }

    public function setUploadAt(\DateTimeImmutable $uploadAt): static
    {
        $this->uploadAt = $uploadAt;

        return $this;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Courses $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        $this->courses->removeElement($course);

        return $this;
    }
}
