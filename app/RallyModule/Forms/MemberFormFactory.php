<?php

declare(strict_types=1);

namespace app\RallyModule\Forms;

use app\AppModule\Forms\FormFactory;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Service\RallyService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette;
use Nette\Application\UI\Form;

class MemberFormFactory extends FormFactory
{
    use Nette\SmartObject;

    public function __construct( private RallyService $service)
    {
    }

    public function create(callable $onSuccess, ?int $teamId): Form
    {
        $form = parent::createForm();
        $form->addText('firstName', 'Jméno:')
            ->setRequired("Prosím, vyplňte jméno.")
            ->addRule(Form::MIN_LENGTH, null, 3)
            ->addRule(Form::MAX_LENGTH, null, 64)
            ->setHtmlAttribute('class', 'form-control')
            ->addCondition($form::FILLED)
            ->addRule(
                [$this, 'isCzechChars'],
                'Pole musí obsahovat pouze znaky české abecedy'
            );

        $form->addText('lastName', 'Příjmení:')
            ->setRequired("Prosím, vyplňte příjmení.")
            ->addRule(Form::MIN_LENGTH, null, 3)
            ->addRule(Form::MAX_LENGTH, null, 64)
            ->setHtmlAttribute('class', 'form-control')
            ->addCondition($form::FILLED)
            ->addRule(
                [$this, 'isCzechChars'],
                'Pole musí obsahovat pouze znaky české abecedy'
            );

        $form->addSelect(
            'type',
            'Typ:',
            array_map(fn(MemberType $type) => $type->getLang(), MemberType::cases())
        )
            ->setRequired("Prosím, vyberte typ.")
            ->setHtmlAttribute('class', 'form-control');

        $form->addSelect(
            'team',
            'Tým:',
            $this->service->getAllTeamIdNames() + [-1 => "Žádný tým"]
        )
            ->setDefaultValue($teamId ?? -1)
            ->setHtmlAttribute('class', 'form-control');

        $form->addSubmit('register', 'Registrovat')
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = function (Form $form, Nette\Utils\ArrayHash $data) use ($onSuccess): void {
            try {
                $this->service->registerMember($data);
            } catch (UniqueConstraintViolationException) {
                $form['firstName']->addError('Uživatel se stejným jménem a příjmením již existuje.');
                $form['lastName']->addError('Uživatel se stejným jménem a příjmením již existuje.');
                return;
            } catch (Nette\Application\BadRequestException $e) {
                $form['type']->addError($e->getMessage());
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
