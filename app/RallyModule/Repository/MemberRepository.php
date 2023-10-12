<?php

namespace app\RallyModule\Repository;

use app\RallyModule\Entity\Member;
use app\RallyModule\Enum\MemberType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Member>
 */
class MemberRepository extends EntityRepository
{
    public function save(Member $member): void
    {
        $this->getEntityManager()->persist($member);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int, Member>
     */
    public function findFullnamesByType(MemberType $type): array
    {
        $query = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.type = :type')
            ->setParameter('type', $type)
            ->getQuery();

        $result = [];

        foreach ($query->getResult() as $entity) {
            $result[$entity->getId()] = $entity->getFirstName() . " " . $entity->getLastName();
        }

        return $result;
    }

    /**
     * @param array<int> $ids
     * @return Collection<int, Member>
     */
    public function getEntitiesByIds(array $ids): Collection
    {
        $query = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery();

        return new ArrayCollection($query->getResult());
    }
}
