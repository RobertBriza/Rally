<?php

namespace app\RallyModule\Service;

use app\RallyModule\Entity\Member;
use app\RallyModule\Entity\Team;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Model\MemberDTO;
use app\RallyModule\Model\TeamDTO;
use app\RallyModule\Repository\MemberRepository;
use app\RallyModule\Repository\TeamRepository;
use app\RallyModule\Util\RallyDataUtil;
use Doctrine\Common\Collections\Collection;
use Nette\Application\BadRequestException;
use Nette\Utils\ArrayHash;

readonly class RallyService
{
    public function __construct(
        private MemberRepository $memberRepository,
        private TeamRepository $teamRepository,
        private RallyDataUtil $rallyDataUtil
    ) {
    }

    /** @return Collection<int, MemberDTO> */
    public function getTeamMembers(int $id): Collection
    {
        $team = $this->teamRepository->findOneSortedByType($id);

        if (!$team) {
            throw new BadRequestException(\sprintf('Team with id %s not found', $id), 404);
        }

        return $team->getMembers()->map(fn (Member $member) => $member->toDTO());
    }

    /** @return array{id: int, name: string} */
    public function getAllTeamIdNames(): array
    {
        return $this->teamRepository->findAllIdNames();
    }

    public function registerMember(ArrayHash $data): void
    {
        $data->type = MemberType::cases()[$data->type];

        foreach ($this->teamRepository->findTeamMemberTypeCount($data->team, $data->type) as $row) {
            if (!$row['type']->isNotMax($row['memberCount'])) {
                throw new BadRequestException(
                    \sprintf(
                        'Tým již obsahuje maximální počet členů typu %s',
                        $row['type']->getLang()
                    )
                );
            }
        }


        $this->memberRepository->save($this->arrayHashToMember($data));
    }
    public function registerTeam(ArrayHash $data): void
    {
        $data->members = $this->rallyDataUtil->mergeMemberIds($data);
        $this->teamRepository->save($this->arrayHashToTeam($data));
    }

    /** @return array<int, TeamDTO> */
    public function getAllTeamsSortedByType(): array
    {
        $entities = $this->teamRepository->findAllSortedByType();

        return \array_map(fn (Team $team) => $team->toDTO(), $entities);
    }

    public function getFullNamesForMultiSelect(MemberType $type): array
    {
        return $this->memberRepository->findFullnamesByType($type);
    }

    public function arrayHashToTeam(ArrayHash $model): Team
    {
        return (new Team)
            ->setName($model->name)
            ->setMembers($this->memberRepository->getEntitiesByIds($model->members));
    }

    public function arrayHashToMember(ArrayHash $model): Member
    {
        $member = (new Member())
            ->setFirstName($model->firstName)
            ->setLastName($model->lastName)
            ->setType($model->type);

        $team = $this->teamRepository->find($model->team);

        if ($team !== null) {
            $member->addTeam($team);
        }

        return $member;
    }
}
