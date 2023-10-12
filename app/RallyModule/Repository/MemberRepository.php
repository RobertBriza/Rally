<?php

namespace app\RallyModule\Repository;

use app\RallyModule\Entity\Member;
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
}
