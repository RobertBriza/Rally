<?php

namespace app\RallyModule\Presenters;

use app\Presenters\BasePresenter;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Forms\TeamFormFactory;
use app\RallyModule\Service\RallyService;
use Nette\Application\UI\Form;

class TeamsPresenter extends BasePresenter
{
    /** @inject */
    public RallyService $service;

    /** @inject */
    public TeamFormFactory $teamFormFactory;

    public function renderDefault(): void
    {
        $this->template->names = $this->service->getAllTeamIdNames();
    }

    public function renderList(): void
    {
        $this->template->teams = $this->service->getAllTeamsSortedByType();
        $this->template->memberTypes = MemberType::cases();
    }

    protected function createComponentRegistrationForm(): Form
    {
        return $this->teamFormFactory->create(
            function (): void {
                $this->flashMessage('Tým byl úspěšně registrován.');
                $this->redirect(':Rally:teams:default');
            }
        );
    }
}
