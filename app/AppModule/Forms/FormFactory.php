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

    public function isCzechChars(Nette\Forms\Controls\TextInput $value): bool
    {
        $allowedChars = 'aábcčdďeéěfghchiíjklmnňoópqrřsštťuúůvwxyýzžAÁBCČDĎEÉĚFGHCHIÍJKLMNŇOÓPQRŘSŠTŤUÚŮVWXYÝZŽ';

        return (bool) preg_match(\sprintf('/^[%s]+$/', preg_quote($allowedChars, '/')), $value->value);
    }
}
