<?php

declare(strict_types=1);

namespace app\AppModule\Forms;

use Nette;
use Nette\Application\UI\Form;


abstract class FormFactory
{
    use Nette\SmartObject;

    public function createForm(): Form
    {
        return new Form;
    }
}
