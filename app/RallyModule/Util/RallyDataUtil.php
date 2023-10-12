<?php

namespace app\RallyModule\Util;

use Nette\Utils\ArrayHash;

class RallyDataUtil
{
    public function mergeMemberIds(ArrayHash $data): array
    {
        $mergedMembers = [];

        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'members_')) {
                $mergedMembers = array_merge($mergedMembers, $value);
                unset($data[$key]);
            }
        }

        return $mergedMembers;
    }


}