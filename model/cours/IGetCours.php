<?php

namespace Application\Model\Cours\IGetCours;

interface IGetCours {
    public function getCours(array $resources): array;
}
