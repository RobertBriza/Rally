<?php

namespace app\RallyModule\Model;

use Doctrine\Common\Collections\Collection;

readonly class TeamDTO
{
    /** @param Collection<int, MemberDTO> $members */
    public function __construct(
        public string $name,
        public Collection $members
    ) {
    }
}
