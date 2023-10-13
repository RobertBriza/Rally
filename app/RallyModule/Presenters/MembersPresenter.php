<?php

namespace app\RallyModule\Presenters;

use app\Presenters\BasePresenter;
use app\RallyModule\Forms\MemberFormFactory;
use app\RallyModule\Service\RallyService;
use Nette\Application\UI\Form;

class MembersPresenter extends BasePresenter
{
    /** @inject */
    public RallyService $service;

    /** @inject */
    public MemberFormFactory $memberFormFactory;

    public function renderCreate(?int $id): void
    {
        $this->template->teamId = $id;
    }

    public function renderList(int $id): void
    {
        $this->template->members = $this->service->getTeamMembers($id);
        $this->template->teamId = $id;
    }

    protected function createComponentRegistrationForm(): Form
    {
        return $this->memberFormFactory->create(
            function (): void {
                $this->flashMessage('Byl jste úspěšně registrován.');
                $this->redirect(':Rally:Teams:default');
            },
            $this->template->teamId
        );
    }
}
