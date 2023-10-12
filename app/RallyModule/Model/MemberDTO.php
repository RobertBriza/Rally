<?php

namespace app\RallyModule\Model;

use app\AppModule\Model\BaseDTO;
use app\RallyModule\Enum\MemberType;

readonly class MemberDTO implements BaseDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public MemberType $type,
        public ?int $team = null
    ) {
    }
}
