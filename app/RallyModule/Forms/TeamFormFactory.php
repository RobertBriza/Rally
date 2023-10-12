<?php

declare(strict_types=1);

namespace app\RallyModule\Forms;

use app\AppModule\Forms\FormFactory;
use app\RallyModule\Enum\MemberType;
use app\RallyModule\Service\RallyService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette;
use Nette\Application\UI\Form;


class TeamFormFactory extends FormFactory
{
    use Nette\SmartObject;

    public function __construct( private RallyService $service)
    {
    }

    public function create(callable $onSuccess): Form
    {
        $form = parent::createForm();
        $form->addText('name', 'Jméno:')
            ->setRequired("Prosím, vyplňte název týmu.")
            ->addRule(Form::MIN_LENGTH, null, 3)
            ->addRule(Form::MAX_LENGTH, null, 64)
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('style', 'margin: 10px;')
            ->addCondition($form::FILLED)
            ->addRule(
                Form::PATTERN,
                'Pole nesmí obsahovat speciální znaky',
                '^[a-zA-Z0-9 ]*$'
            );

        foreach (MemberType::cases() as $key => $case) {
            $members = $this->service->getFullNamesForMultiSelect($case);

            $limits = $case->getMinMaxForMultiSelect();

            $multiSelect = $form->addMultiSelect(
                'members_' . $key,
                $case->getLang(),
                $members
            )
                ->addRule(
                    Form::LENGTH,
                    'Vyberte členy pole ' . $case->getLang() . ' v počtu at mezi %d az %d ',
                    $limits
                )
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('style', 'margin: 10px;');

            if ($case !== MemberType::PHOTOGRAPHER) {
                $multiSelect->setRequired(\sprintf('Musíte vybrat alespoň %d a maximálně %d členy', ...$limits));
            }

        }

        $form->addSubmit('register', 'Registrovat')
            ->setHtmlAttribute('class', 'btn btn-primary')
            ->setHtmlAttribute('style', 'margin-top: 20px;');

        $form->onSuccess[] = function (Form $form, Nette\Utils\ArrayHash $data) use ($onSuccess): void {
            try {
                $this->service->registerTeam($data);
            } catch (UniqueConstraintViolationException) {
                $form['firstName']->addError('Uživatel se stejným jménem a příjmením již existuje.');
                $form['lastName']->addError('Uživatel se stejným jménem a příjmením již existuje.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
