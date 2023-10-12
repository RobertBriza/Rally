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
    public function save(Team $team): void
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();
    }

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
            ->orderBy('t.name', 'ASC')
            ->addOrderBy('FIELD(m.type, :types)')
            ->setParameter('types', array_map(fn(MemberType $type) => $type->value, MemberType::cases()))
            ->getQuery()
            ->getResult();
    }

    public function findOneSortedByType(int $id): ?Team
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'm')
            ->leftJoin('t.members', 'm')
            ->where('t.id = :id')
            ->orderBy('t.name', 'ASC')
            ->addOrderBy('FIELD(m.type, :types)')
            ->setParameter('id', $id)
            ->setParameter('types', array_map(fn(MemberType $type) => $type->value, MemberType::cases()))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTeamMemberTypeCount(int $id, MemberType $type): array
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(m.id) AS memberCount', 'm.type')
            ->leftJoin('t.members', 'm')
            ->where('t.id = :id')
            ->andWhere('m.type = :type')
            ->setParameter('id', $id)
            ->setParameter('type', $type)
            ->groupBy('m.type')
            ->getQuery()
            ->getResult();
    }
}
