<?php

namespace app\RallyModule\Service;

use app\RallyModule\Entity\Member;
use app\RallyModule\Entity\Team;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Model\MemberDTO;
use app\RallyModule\Model\TeamDTO;
use app\RallyModule\Repository\MemberRepository;
use app\RallyModule\Repository\TeamRepository;
use Doctrine\Common\Collections\Collection;
use Nette\Application\BadRequestException;
use Nette\Utils\ArrayHash;

readonly class RallyService
{
    public function __construct(
        private MemberRepository $memberRepository,
        private MemberMapper $memberMapper,
        private TeamRepository $teamRepository,
        private TeamMapper $teamMapper
    ) {
    }

    /** @return Collection<int, MemberDTO> */
    public function getTeamMembers(int $id): Collection
    {
        $team = $this->teamRepository->find($id);

        if (!$team) {
            throw new BadRequestException(\sprintf('Team with id %s not found', $id), 404);
        }

        return $team->getMembers()->map(fn (Member $member) => $this->memberMapper->toDTO($member));
    }

    /** @return array{id: int, name: string} */
    public function getAllTeamIdNames(): array
    {
        return $this->teamRepository->findAllIdNames();
    }

    public function registerMember(ArrayHash $data): void
    {
        $data->type = MemberType::cases()[$data->type];

        $this->memberRepository->save($this->memberMapper->toEntity($data));
    }

    /** @return array<int, TeamDTO> */
    public function getAllTeamsSortedByType(): array
    {
        $entities = $this->teamRepository->findAllSortedByType();

        return \array_map(fn (Team $team) => $this->teamMapper->toDTO($team), $entities);
    }
}
