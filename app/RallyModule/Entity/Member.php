<?php

namespace app\RallyModule\Entity;

use app\RallyModule\Enum\MemberType;
use app\RallyModule\Model\MemberDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="app\RallyModule\Repository\MemberRepository")
 * @ORM\Table(name="member", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="unique_firstname_lastname", columns={"first_name", "last_name"})
 *  })
 */
class Member
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", enumType=MemberType::class, length=16)
     */
    private MemberType $type;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="app\RallyModule\Entity\Team",
     *     mappedBy="members",
     *     cascade={"persist", "remove"}
     * )

     * @var Collection<int, Team>
     */
    private Collection $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getType(): MemberType
    {
        return $this->type;
    }

    public function setType(MemberType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function setTeams(Collection $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addMember($this);
        }

        return $this;
    }

    public function toDTO(): MemberDTO
    {
        return new MemberDTO(
            $this->getFirstName(),
            $this->getLastName(),
            $this->getType()
        );
    }
}
