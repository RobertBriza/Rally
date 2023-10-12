<?php

namespace app\RallyModule\Service;

use app\AppModule\Entity\BaseEntity;
use app\AppModule\Model\BaseDTO;
use app\AppModule\Service\MapperInterface;
use app\RallyModule\Entity\Member;
use app\RallyModule\Model\MemberDTO;
use app\RallyModule\Repository\TeamRepository;
use Nette\Utils\ArrayHash;

class MemberMapper implements MapperInterface
{
    public function __construct(private TeamRepository $teamRepository)
    {
    }

    public function toDTO(BaseEntity $entity): MemberDTO
    {
        if (!$entity instanceof Member) {
            throw new \InvalidArgumentException(\sprintf('Entity must be instance of %s', Member::class));
        }

        return new MemberDTO(
            $entity->getFirstName(),
            $entity->getLastName(),
            $entity->getType()
        );
    }

    public function toEntity(BaseDTO|ArrayHash $model): Member
    {
        if (!$model instanceof MemberDTO && !$model instanceof ArrayHash) {
            throw new \InvalidArgumentException(\sprintf(
                'Entity %s must be instance of %s or %s',
                $model::class,
                MemberDTO::class,
                ArrayHash::class
            ));
        }

        $team = $this->teamRepository->find($model->team);

        $member = (new Member())
            ->setFirstName($model->firstName)
            ->setLastName($model->lastName)
            ->setType($model->type);

        if ($team !== null) {
            $member->addTeam($team);
        }

        return $member;
    }
}
