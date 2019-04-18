<?php

namespace Logic\Unification;

use Logic\Solution;
use Logic\Solutions;

class OrLogic
{
    public function unify(Solutions $solutionsA, Solutions $solutionsB)
    {
        return new Solutions(array_merge(
            $solutionsA->getArrayCopy(),
            $solutionsB->getArrayCopy()
        ));
    }
}