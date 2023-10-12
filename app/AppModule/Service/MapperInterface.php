<?php

namespace app\AppModule\Service;

use app\AppModule\Entity\BaseEntity;
use app\AppModule\Model\BaseDTO;
use Nette\Utils\ArrayHash;

interface MapperInterface
{
    public function toDTO(BaseEntity $entity): BaseDTO;

    public function toEntity(BaseDTO|ArrayHash $model): BaseEntity;
}
