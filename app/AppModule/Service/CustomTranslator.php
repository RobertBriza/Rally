<?php

namespace app\AppModule\Service;

use Contributte\Translation\LocalesResolvers\Session;
use Nette\Localization\Translator;

class CustomTranslator
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {

        $this->translator = $translator;
    }

    public function translate($message, ...$parameters): string
    {
        $prefixedMessage = 'lng.' . $message;

        return $this->translator->translate($prefixedMessage, ...$parameters);
    }

    public function getLocale(): string
    {
        return$_SESSION['__NF']['DATA'][Session::class]['locale'] ?? 'en';
    }
}