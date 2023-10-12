<?php

namespace app\RallyModule\Enum;

enum MemberType: string
{
    case DRIVER = 'závodník';
    case TECHNICIAN = 'technik';
    case MANAGER = 'manažer';
    case CO_DRIVER = 'spolujezdec';
    case PHOTOGRAPHER = 'fotograf';
}
