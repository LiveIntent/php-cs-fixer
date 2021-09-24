<?php

namespace LiveIntent\PhpCsFixer\Fixer\Naming;

use SplFileInfo;
use Illuminate\Support\Str;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use LiveIntent\PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use LiveIntent\PhpCsFixer\Util\LaravelIdentifier;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

class ClassNumberFixer extends AbstractFixer
{
    /**
     * Mapping of laravel components to their expected class number.
     *
     * @var array
     */
    public const NUMBERS = [
        'command' => 'singular',
        'controller' => 'singular',
        'factory' => 'singular',
        'form_request' => 'singular',
        'model' => 'singular',
        'resource' => 'singular',
        'resource_collection' => 'plural',
    ];

    /**
     * Check if the fixer is a candidate for given Tokens collection.
     *
     * Fixer is a candidate when the collection contains tokens that may be fixed
     * during fixer work. This could be considered as some kind of bloom filter.
     * When this method returns true then to the Tokens collection may or may not
     * need a fixing, but when this method returns false then the Tokens collection
     * need no fixing for sure.
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound([T_CLASS]);
    }

    /**
     * Check if fixer is risky or not.
     *
     * Risky fixer could change code behavior!
     */
    public function isRisky(): bool
    {
        return true;
    }

    /**
     * Fixes a file.
     */
    public function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        if (! $component = LaravelIdentifier::identify($file)) {
            return;
        }

        if (! $number = static::NUMBERS[$component] ?? null) {
            return;
        }

        $suffix = ClassSuffixFixer::SUFFIXES[$component] ?? '';
        [$classNameToken, $classNameTokenIndex] = $this->findClassNameToken($tokens);

        $subject = Str::beforeLast($classNameToken->getContent(), $suffix);

        $adjusted = $number === 'singular' ? Str::singular($subject) : Str::plural($subject);

        $tokens[$classNameTokenIndex] = new Token($adjusted.$suffix);
    }

    /**
     * Returns the definition of the fixer.
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Enforce class names use the proper grammatical number.',
            [],
            null,
            'Renames classes and cannot rename the files or references. You might need to do additional manual fixing.'
        );
    }

    /**
     * Returns true if the file is supported by this fixer.
     *
     * @return bool true if the file is supported by this fixer, false otherwise
     */
    public function supports(SplFileInfo $file): bool
    {
        return ! Str::contains($file->getPath(), ['migrations']);
    }

    /**
     * Get the token representing the name of the class.
     */
    protected function findClassNameToken(Tokens $tokens): array|null
    {
        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind(T_CLASS)) {
                $classNameTokenIndex = $index + 2;

                return [$tokens[$classNameTokenIndex], $classNameTokenIndex];
            }
        }

        return null;
    }
}
