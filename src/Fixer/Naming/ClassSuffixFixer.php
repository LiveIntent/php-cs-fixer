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

class ClassSuffixFixer extends AbstractFixer
{
    /**
     * Mapping of laravel components to their expected suffix. Null
     * values indicate that the class should _not_ be suffixed.
     *
     * @var array
     */
    private static $suffixes = [
        'command' => 'Command',
        'controller' => 'Controller',
        'event' => null,
        'exception' => 'Exception',
        'factory' => 'Factory',
        'form_request' => 'Request',
        'job' => 'Job',
        'listener' => 'Listener',
        'mailable' => 'Mail',
        'middleware' => null,
        'model' => null,
        'notification' => 'Notification',
        'provider' => 'ServiceProvider',
        'resource' => 'Resource',
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

        $suffix = static::$suffixes[$component];
        [$classNameToken, $classNameTokenIndex] = $this->findClassNameToken($tokens);

        $adjusted = $suffix
            ? Str::finish($classNameToken->getContent(), $suffix)
            : Str::beforeLast($classNameToken->getContent(), Str::studly($component));

        $tokens[$classNameTokenIndex] = new Token($adjusted);
    }

    /**
     * Returns the definition of the fixer.
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Enforce classes are properly suffixed.',
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
