<?php

namespace app\RallyModule\Model;

use app\AppModule\Model\BaseDTO;
use Doctrine\Common\Collections\Collection;

readonly class TeamDTO implements BaseDTO
{
    /** @param Collection<int, MemberDTO> $members */
    public function __construct(
        public string $name,
        public Collection $members
    ) {
    }
}
