<?php

namespace app\RallyModule\Presenters;

use app\AppModule\Forms\MemberFormFactory;
use app\Presenters\BasePresenter;
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

    protected function createComponentRegistrationForm(): Form
    {
        return $this->memberFormFactory->create(
            function (): void {
                $this->flashMessage('Byl jste úspěšně přihlášen.');
                $this->redirect(':Rally:teams:default');
            },
            $this->template->teamId
        );
    }
}
