<?php

namespace app\RallyModule\Enum;

enum MemberType: string
{
    case DRIVER = 'driver';

    case CO_DRIVER = 'codriver';
    case TECHNICIAN = 'technician';
    case MANAGER = 'manager';
    case PHOTOGRAPHER = 'photographer';

    public function getLang(): string
    {
        return 'member.' . $this->value;
    }

    public function getMinMaxForMultiSelect(): array
    {
        match ($this) {
            self::DRIVER, self::CO_DRIVER => $limits = [1, 3],
            self::TECHNICIAN => $limits = [1, 2],
            self::MANAGER => $limits = [1, 1],
            self::PHOTOGRAPHER => $limits = [0, 1]
        };

        return $limits;
    }

    public function getInfo(): string
    {
        return \sprintf("Minimálně %s, maximálně %s", ...$this->getMinMaxForMultiSelect());
    }

    public function isNotMax(int $count): bool
    {
        [$min, $max] = $this->getMinMaxForMultiSelect();

        return $count < $max;
    }

    public function getMaxErrorLang(): string
    {
        match ($this) {
            self::DRIVER, self::CO_DRIVER => $lang = 'field.member.max.three',
            self::TECHNICIAN => $lang = 'field.member.max.two',
            self::MANAGER, self::PHOTOGRAPHER => $lang = 'field.member.max.one'
        };

        return $lang;
    }
}
