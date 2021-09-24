<?php

namespace LiveIntent\PhpCsFixer;

use PhpCsFixer\Config as BaseConfig;
use LiveIntent\PhpCsFixer\RuleSet\Set\LiveIntentSet;

class Config extends BaseConfig
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->registerCustomFixers([
            new \LiveIntent\PhpCsFixer\Fixer\Naming\ClassSuffixFixer(),
            new \LiveIntent\PhpCsFixer\Fixer\Naming\ClassNumberFixer(),
        ]);

        $this->setRules(
            (new LiveIntentSet())->getRules()
        );
    }
}
