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

class MemberFormFactory extends FormFactory
{
    use Nette\SmartObject;

    public function __construct(
        private readonly RallyService $service,
        private readonly CustomTranslator $t
    ) {
    }

    public function create(callable $onSuccess, ?int $teamId): Form
    {
        $form = parent::createForm();
        $form->addText('firstName', $this->t->translate('name'))
            ->setRequired($this->t->translate('field.firstName.required'))
            ->addRule(Form::MIN_LENGTH, null, 3)
            ->addRule(Form::MAX_LENGTH, null, 64)
            ->setHtmlAttribute('class', 'form-control')
            ->addCondition($form::FILLED)
            ->addRule(
                Form::PATTERN,
                $this->t->translate('field.czechchars'),
                '^[a-zA-ZěščřžýáíéůúňťďĚŠČŘŽÝÁÍÉŮÚŇŤĎ]*$'
            );

        $form->addText('lastName', $this->t->translate('lastName'))
            ->setRequired($this->t->translate('field.lastName.required'))
            ->addRule(Form::MIN_LENGTH, null, 3)
            ->addRule(Form::MAX_LENGTH, null, 64)
            ->setHtmlAttribute('class', 'form-control')
            ->addCondition($form::FILLED)
            ->addRule(
                Form::PATTERN,
                $this->t->translate('field.czechchars'),
                '^[a-zA-ZěščřžýáíéůúňťďĚŠČŘŽÝÁÍÉŮÚŇŤĎ]*$'
            );

        $form->addSelect(
            'type',
            $this->t->translate('type'),
            array_map(
                fn(MemberType $type) => $this->t->translate($type->getLang()),
                MemberType::cases()
            )
        )
            ->setRequired($this->t->translate('field.type.required'))
            ->setHtmlAttribute('class', 'form-control');

        $form->addSelect(
            'team',
            $this->t->translate('name'),
            $this->service->getAllTeamIdNames() + [-1 => $this->t->translate('team.empty')]
        )
            ->setDefaultValue($teamId ?? -1)
            ->setHtmlAttribute('class', 'form-control');

        $form->addSubmit('register', $this->t->translate('register'))
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = function (Form $form, Nette\Utils\ArrayHash $data) use ($onSuccess): void {
            try {
                $this->service->registerMember($data);
            } catch (UniqueConstraintViolationException) {
                $form['firstName']->addError($this->t->translate('field.user.exists'));
                $form['lastName']->addError($this->t->translate('field.user.exists'));
                return;
            } catch (Nette\Application\BadRequestException $e) {
                $form['type']->addError($this->t->translate(
                    'team.max.reached',
                    ['name' => $this->t->translate($data->type->getLang())]));
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
