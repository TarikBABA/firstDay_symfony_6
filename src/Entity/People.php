<?php

namespace App\Entity;

use App\Traits\TimeStampTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PeopleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[ORM\HasLifecycleCallbacks]

class People
{
    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message : "Veuillez renseigner ce champ")]
    #[Assert\Length(min:4,max:20, minMessage:"minErrorMessage",maxMessage:"MaxErrorMessage")]
    // /**
    //  * @ORM\Column(type="string",length="50")
    //  * @Assert\NotBlank(message="vide")
    //  * @Assert\Length(min=4, minMessage="minErrorMessage")
    //  */
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'people')]
    private ?User $createdBy = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}