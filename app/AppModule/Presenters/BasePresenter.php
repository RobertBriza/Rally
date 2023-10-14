<?php

namespace app\Presenters;

use app\AppModule\Service\CustomTranslator;
use Contributte;
use Nette;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @inject */
    public CustomTranslator $t;

    /** @inject */
    public Contributte\Translation\LocalesResolvers\Session $translatorSessionResolver;

    protected function startup(): void
    {
        parent::startup();
        $this->getSession()->start();
    }

    public function handleChangeLocale(): void
    {
        $defaultLocale = 'cs';

        $locale = $this->t->getLocale() === $defaultLocale ? 'en' : $defaultLocale;

        $this->translatorSessionResolver->setLocale($locale);
        $this->redirect('this');
    }
}
