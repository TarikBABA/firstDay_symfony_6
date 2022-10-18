<?php

namespace App\Entity;

use App\Repository\PeopleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[ORM\HasLifecycleCallbacks]

class People
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $age = null;

    #[ORM\OneToOne(inversedBy: 'people', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    #[ORM\ManyToMany(targetEntity: Hobby::class)]
    private Collection $Hobbies;

    #[ORM\ManyToOne(inversedBy: 'people')]
    private ?Job $Job = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    public function __construct()
    {
        $this->Hobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }


    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->Hobbies;
    }

    public function addHobby(Hobby $hobby): self
    {
        if (!$this->Hobbies->contains($hobby)) {
            $this->Hobbies->add($hobby);
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): self
    {
        $this->Hobbies->removeElement($hobby);

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->Job;
    }

    public function setJob(?Job $Job): self
    {
        $this->Job = $Job;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }


    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updateAt = new \DateTime();
    }


    #[ORM\PreUpdate]
    // /**
    //  * @ORM\PreUpdate
    //  */
    public function onPreUpdate()
    {
        $this->updateAt = new \DateTime();
    }
}