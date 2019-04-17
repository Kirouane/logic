<?php

namespace Logic\Unification;

use Logic\Option;
use Logic\Options;

class AndLogic
{
    public function unify(Options $optionsA, Options $optionsB)
    {
        $options = new Options();
        /** @var Option $optionA */
        foreach ($optionsA as $optionA) {
            /** @var Option $optionB */
            foreach ($optionsB as $optionB) {
                $match = $optionA->match($optionB);
                if (count($match)) {
                    $options[] = $match;
                }
            }
        }

        return $options;
    }
}