<?php

namespace app\RallyModule\Entity;

use app\AppModule\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="app\RallyModule\Repository\TeamRepository")
 */
class Team implements BaseEntity
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
    private string $name;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="app\RallyModule\Entity\Member",
     *     inversedBy="teams",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinTable(name="team_members")
     * @var Collection<int, Member>
     */
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function setMembers(Collection $members): void
    {
        $this->members = $members;
    }

    public function addMember(Member $member): self
    {
        if(!$this->members->contains($member)) {
            $this->members->add($member);
            $member->addTeam($this);
        }

        return $this;
    }
}
