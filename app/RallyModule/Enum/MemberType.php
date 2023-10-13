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
        match ($this) {
            self::DRIVER => $lang = 'Závodník',
            self::CO_DRIVER => $lang = 'Spolujezdec',
            self::TECHNICIAN => $lang = 'Technik',
            self::MANAGER => $lang = 'Manažer',
            self::PHOTOGRAPHER => $lang = 'Fotograf'
        };

        return $lang;
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
}
