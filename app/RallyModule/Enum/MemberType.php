<?php

namespace app\RallyModule\Enum;

enum MemberType: string
{
    case DRIVER = 'závodník';

    case CO_DRIVER = 'spolujezdec';
    case TECHNICIAN = 'technik';
    case MANAGER = 'manažer';
    case PHOTOGRAPHER = 'fotograf';
}
