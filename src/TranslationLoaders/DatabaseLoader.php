<?php

namespace Thomasvvugt\LaraLang\TranslationLoaders;

use Thomasvvugt\LaraLang\Translation;
use Thomasvvugt\LaraLang\Language;
use Thomasvvugt\LaraLang\Exceptions\InvalidConfiguration;

class DatabaseLoader implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace): array
    {
        $translation = $this->getConfiguredModelClass();
        return $translation::getTranslationsForGroup($locale, $group, $namespace);
    }

    protected function getConfiguredModelClass(): string
    {
        $modelClass = config('laralang.models.translation');
        if (! is_a(new $modelClass, Translation::class)) {
            throw InvalidConfiguration::invalidModel($modelClass);
        }
        return $modelClass;
    }
}