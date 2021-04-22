<?php

namespace LiveIntent\PHPCsRules;

class PHPCsRules
{
    /**
     * Return the list of rules.
     *
     * @return array
     */
    public static function getRules()
    {
        return require(__DIR__.'/rules.php');
    }
}
