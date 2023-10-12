<?php

namespace app\RallyModule\Repository;

use app\RallyModule\Entity\Team;
use app\RallyModule\Enum\MemberType;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Team>
 */
class TeamRepository extends EntityRepository
{
    /** @return array{id: int, name: string} */
    public function findAllIdNames(): array
    {
        $query = $this->createQueryBuilder('t')
            ->select('t.id', 't.name')
            ->getQuery();

        $associativeResult = [];

        foreach ($query->toIterable() as $row) {
            $associativeResult[$row['id']] = $row['name'];
        }

        return $associativeResult;
    }

    /** @return array<int, Team> */
    public function findAllSortedByType(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'm')
            ->leftJoin('t.members', 'm')
            ->orderBy('FIELD(m.type, :types)')
            ->setParameter('types', array_map(fn(MemberType $type) => $type->value, MemberType::cases()))
            ->getQuery()
            ->getResult();
    }
}
