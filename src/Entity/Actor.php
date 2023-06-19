<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(
    fields: ['name'],
    message: 'Ce acteur existe déjà')]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(name: 'name', type: 'string', length: 255, unique: true)]
    private ?string $name = null;


    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'actors')]
    private Collection $programs;

    #[Assert\EnableAutoMapping]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $actorPicture = null;

    #[Vich\UploadableField(mapping: 'actor_file', fileNameProperty: 'actorPicture')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $actorFile = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return File|null
     */
    public function getActorFile(): ?File
    {
        return $this->actorFile;
    }

    /**
     * @param File|null $actorFile
     */
    public function setActorFile(File $image = null): Actor
    {
        $this->actorFile = $image;
        if($image) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Program>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        $this->programs->removeElement($program);

        return $this;
    }

    public function getActorPicture(): ?string
    {
        return $this->actorPicture;
    }

    public function setActorPicture(?string $actorPicture): self
    {
        $this->actorPicture = $actorPicture;

        return $this;
    }
}
