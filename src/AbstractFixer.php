<?php

namespace LiveIntent\PhpCsFixer;

use PhpCsFixer\AbstractFixer as BaseAbstractFixer;

abstract class AbstractFixer extends BaseAbstractFixer
{
    public const VENDOR_NAME = 'LiveIntent';

    /**
     * Returns the name of the fixer.
     *
     * The name must be all lowercase and without any spaces.
     */
    public function getName(): string
    {
        return self::VENDOR_NAME . '/' . parent::getName();
    }
}
