<?php

declare(strict_types=1);

namespace app\RallyModule\Forms;

use app\AppModule\Forms\FormFactory;
use app\AppModule\Service\CustomTranslator;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Service\RallyService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette;
use Nette\Application\UI\Form;


class TeamFormFactory extends FormFactory
{
    use Nette\SmartObject;

    public function __construct(
        private readonly RallyService $service,
        private readonly CustomTranslator $t
    ) {
    }

    public function create(callable $onSuccess): Form
    {
        $form = parent::createForm();
        $form->addText('name', $this->t->translate('team.name'))
            ->setRequired($this->t->translate('field.team.required'))
            ->addRule(Form::MIN_LENGTH, null, 3)
            ->addRule(Form::MAX_LENGTH, null, 64)
            ->setHtmlAttribute('class', 'form-control')
            ->addCondition($form::FILLED)
            ->addRule(
                Form::PATTERN,
                $this->t->translate('field.nospecialchars'),
                '^[a-zA-Z0-9 ěščřžýáíéůúňťďĚŠČŘŽÝÁÍÉŮÚŇŤĎ]*$'
            );

        foreach (MemberType::cases() as $key => $case) {
            $members = $this->service->getFullNamesForMultiSelect($case);

            $limits = $case->getMinMaxForMultiSelect();

            $multiSelect = $form->addMultiSelect(
                'members_' . $key,
                $this->t->translate($case->getLang()),
                $members
            )
                ->addRule(
                    Form::MAX_LENGTH,
                    $this->t->translate($case->getMaxErrorLang(), [
                        'name' => $this->t->translate($case->getLang())
                    ]),
                    $limits[1]
                )
                ->setHtmlAttribute('class', 'form-control')
                ->setOption(
                    "description",
                    $this->t->translate('field.member.range', $case->getMinMaxForMultiSelect())
                );


            if ($case !== MemberType::PHOTOGRAPHER) {
                $multiSelect->setRequired($this->t->translate('field.member.min.one', [
                    'name' => $this->t->translate($case->getLang())
                ]));
            }
        }

        $form->addSubmit('register', 'Registrovat')
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = function (Form $form, Nette\Utils\ArrayHash $data) use ($onSuccess): void {
            try {
                $this->service->registerTeam($data);
            } catch (UniqueConstraintViolationException) {
                $form['name']->addError($this->t->translate('field.team.exists'));
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
