<?php

namespace Thomasvvugt\LaraLang\Exceptions;

use Exception;
use Thomasvvugt\LaraLang\Translation;
use Thomasvvugt\LaraLang\Language;

class InvalidConfiguration extends Exception
{
    public static function invalidModel(string $className): self
    {
        return new static("You have configured an invalid class `{$className}`.".
            'A valid class extends '.Translation::class.', or '.Language::class.'.');
    }
}