<?php

namespace Logic\Unification;

use Logic\Solution;
use Logic\Solutions;

class OrLogic
{
    public function unify(Solutions $solutionsA, Solutions $solutionsB)
    {
        $solutions = new Solutions();
        /** @var Solution $solutionA */
        foreach ($solutionsA as $solutionA) {
            /** @var Solution $solutionB */
            foreach ($solutionsB as $solutionB) {
                $match = $solutionA->match($solutionB);
                if (count($match)) {
                    $solutions[] = $match;
                }
            }
        }

        return $solutions;
    }
}