<?php

namespace app\RallyModule\Presenters;

use app\AppModule\Forms\MemberFormFactory;
use app\Presenters\BasePresenter;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Service\RallyService;

class TeamsPresenter extends BasePresenter
{
    /** @inject */
    public RallyService $service;

    /** @inject */
    public MemberFormFactory $memberFormFactory;

    public function renderDefault(): void
    {
        $this->template->names = $this->service->getAllTeamIdNames();
    }

    public function renderDetail(int $id): void
    {
        $this->template->members = $this->service->getTeamMembers($id);
    }

    public function renderList(): void
    {
        $this->template->teams = $this->service->getAllTeamsSortedByType();
        $this->template->memberTypes = MemberType::cases();
    }
}
