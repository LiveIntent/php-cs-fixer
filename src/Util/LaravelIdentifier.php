<?php

namespace LiveIntent\PhpCsFixer\Util;

use Illuminate\Support\Str;

class LaravelIdentifier
{
    /**
     * The file to analyze.
     *
     * @var \SplFileInfo
     */
    private $file;

    /**
     * A reflection class about the file in question.
     *
     * @var \ReflectionClass
     */
    private $class;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(\SplFileInfo $file)
    {
        $composer = json_decode(file_get_contents('composer.json'));
        $psr4 = array_merge(
            (array) data_get($composer, 'autoload.psr-4', []),
            (array) data_get($composer, 'autoload-dev.psr-4', []),
        );

        // assume this command is run from the project root
        $basePath = getcwd();

        $fqcn = str_replace(
            [...array_values($psr4), $basePath, '.php', '/'],
            [...array_keys($psr4), '', '', '\\'],
            $file->getPathname()
        );

        $this->class = new \ReflectionClass($fqcn);

        $this->file = $file;
    }

    /**
     * Identify the Laravel component in the analyzed file.
     */
    public static function identify(\SplFileInfo $file): string|null
    {
        $instance = new static($file);

        $identifiers = array_filter(get_class_methods($instance), fn ($m) => Str::startsWith($m, 'is'));

        foreach ($identifiers as $identifier) {
            if ($instance->{$identifier}()) {
                return Str::of($identifier)->after('is')->snake()->lower()->__toString();
            }
        }

        return null;
    }

    protected function inDir(string $dir): bool
    {
        return Str::contains($this->file->getPath(), $dir);
    }

    protected function inheritsFrom(string $parent): bool
    {
        $reflectionClass = $this->class;

        while ($reflectionClass->getParentClass()) {
            if ($reflectionClass->isSubclassOf($parent)) {
                return true;
            }

            $reflectionClass = $reflectionClass->getParentClass();
        }

        return false;
    }

    protected function uses(string $trait): bool
    {
        $reflectionClass = $this->class;

        do {
            if (in_array($trait, $reflectionClass->getTraitNames())) {
                return true;
            }

            $reflectionClass = $reflectionClass->getParentClass();
        } while ($reflectionClass->getParentClass());

        return false;
    }

    public function isCommand(): bool
    {
        return $this->inheritsFrom(\Illuminate\Console\Command::class);
    }

    public function isController(): bool
    {
        return $this->inheritsFrom(\Illuminate\Routing\Controller::class);
    }

    public function isEvent(): bool
    {
        return $this->inDir('Events') && $this->uses(\Illuminate\Foundation\Events\Dispatchable::class);
    }

    public function isException(): bool
    {
        return $this->inheritsFrom(\Exception::class);
    }

    public function isFactory(): bool
    {
        return $this->inheritsFrom(\Illuminate\Database\Eloquent\Factories\Factory::class);
    }

    public function isFormRequest(): bool
    {
        return $this->inheritsFrom(\Illuminate\Foundation\Http\FormRequest::class);
    }

    public function isJob(): bool
    {
        return $this->inDir('Jobs') && $this->class->hasMethod('handle');
    }

    public function isListener(): bool
    {
        return $this->inDir('Listeners') && $this->class->hasMethod('handle');
    }

    public function isMailable(): bool
    {
        return $this->inheritsFrom(\Illuminate\Mail\Mailable::class);
    }

    public function isMiddleware(): bool
    {
        return $this->inDir('Middleware') && $this->class->hasMethod('handle');
    }

    public function isModel(): bool
    {
        return $this->inheritsFrom(\Illuminate\Database\Eloquent\Model::class);
    }

    public function isNotification(): bool
    {
        return $this->inheritsFrom(\Illuminate\Notifications\Notification::class);
    }

    public function isProvider(): bool
    {
        return $this->inheritsFrom(\Illuminate\Support\ServiceProvider::class);
    }

    public function isResource(): bool
    {
        return $this->inheritsFrom(\Illuminate\Http\Resources\Json\JsonResource::class);
    }
}
