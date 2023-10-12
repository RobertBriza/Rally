<?php

namespace app\RallyModule\Service;

use app\AppModule\Entity\BaseEntity;
use app\AppModule\Model\BaseDTO;
use app\AppModule\Service\MapperInterface;
use app\RallyModule\Entity\Member;
use app\RallyModule\Entity\Team;
use app\RallyModule\Model\TeamDTO;
use Nette\Utils\ArrayHash;

class TeamMapper implements MapperInterface
{
    public function __construct(private MemberMapper $memberMapper)
    {
    }

    public function toDTO(BaseEntity $entity): TeamDTO
    {
        if (!$entity instanceof Team) {
            throw new \InvalidArgumentException(\sprintf('Entity must be instance of %s', Member::class));
        }

        return new TeamDTO(
            $entity->getName(),
            $entity->getMembers()->map(fn (Member $member) => $this->memberMapper->toDTO($member))
        );
    }

    public function toEntity(BaseDTO|ArrayHash $model): Member
    {
        //TODO: yet to be implemented
    }
}
